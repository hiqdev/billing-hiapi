<?php
declare(strict_types=1);

/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target\ChangePlan;

use DateTimeImmutable;
use hiapi\exceptions\NotAuthorizedException;
use hiapi\legacy\lib\billing\plan\Forker\PlanForkerInterface;
use hiqdev\billing\hiapi\target\ChangePlan\Strategy\PlanChangeStrategyProviderInterface;
use hiqdev\billing\hiapi\tools\PermissionCheckerInterface;
use hiqdev\billing\mrdp\Sale\HistoryAndFutureSaleRepository;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\DataMapper\Repository\ConnectionInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\Exception\ConstraintException;
use hiqdev\php\billing\Exception\InvariantException;
use hiqdev\php\billing\Exception\RuntimeException;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\php\billing\tools\CurrentDateTimeProviderInterface;
use hiqdev\yii\DataMapper\Repository\Connection;
use Psr\Log\LoggerInterface;
use Throwable;
use yii\web\User;

/**
 * Schedules tariff plan change
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Action
{
    /**
     * @var ConnectionInterface|Connection
     */
    private ConnectionInterface $connection;
    private TargetRepositoryInterface $targetRepo;
    private HistoryAndFutureSaleRepository $saleRepo;
    private PlanForkerInterface $planForker;
    private LoggerInterface $log;
    private DateTimeImmutable $currentTime;
    private User $user;
    private PlanChangeStrategyProviderInterface $strategyProvider;
    private Strategy\PlanChangeStrategyInterface $strategy;
    private PermissionCheckerInterface $permissionChecker;

    public function __construct(
        ConnectionInterface $connection,
        TargetRepositoryInterface $targetRepo,
        HistoryAndFutureSaleRepository $saleRepo,
        PlanForkerInterface $planForker,
        LoggerInterface $log,
        CurrentDateTimeProviderInterface $currentDateTimeProvider,
        User $user,
        PlanChangeStrategyProviderInterface $strategyProvider,
        PermissionCheckerInterface $permissionChecker,
        private ActiveSaleFinder $activeSaleFinder
    ) {
        $this->targetRepo = $targetRepo;
        $this->saleRepo = $saleRepo;
        $this->planForker = $planForker;
        $this->connection = $connection;
        $this->log = $log;
        $this->user = $user;
        $this->currentTime = $currentDateTimeProvider->dateTimeImmutable();
        $this->strategyProvider = $strategyProvider;
        $this->permissionChecker = $permissionChecker;
    }

    public function __invoke(Command $command): TargetInterface
    {
        $this->permissionChecker->ensureCustomerCan($command->customer, 'have-goods');
        $target = $this->getTarget($command);
        $customer = $command->customer;
        assert($customer !== null);
        assert($command->time instanceof DateTimeImmutable);

        $activeSale = $this->findActiveSale($target, $customer, $command->time);
        if ($activeSale === null) {
            throw new InvariantException('Plan can\'t be changed: there is no active sale for the given date');
        }
        $this->strategy = $this->strategyProvider->getBySale($activeSale);

        $this->ensureNotHappeningInPast($command->time, $command->wall_time);

        $timeInPreviousSalePeriod = $this->strategy->calculateTimeInPreviousSalePeriod($activeSale, $command->time);
        $previousSale = $this->findActiveSale($target, $customer, $timeInPreviousSalePeriod);
        $target = $this->tryToCancelScheduledPlanChange($activeSale, $previousSale, $command->time, $command->plan);
        if ($target !== null) {
            return $target;
        }
        $target = $this->tryToChangeScheduledPlanChange($activeSale, $customer, $command->time, $command->plan);

        return $target ?? $this->schedulePlanChange($activeSale, $customer, $command->time, $command->plan);
    }

    public function withActiveSaleFinder(ActiveSaleFinder $activeSaleFinder): self
    {
        $new = clone $this;
        $new->activeSaleFinder = $activeSaleFinder;

        return $new;
    }

    /**
     * If there is a scheduled plan change from tariff "A" to "B" at some specific Date "D",
     * and we receive a new plan change request to change plan to "A" since Date "D", then:
     *
     * 1. Scheduled plan change should be cancelled
     * 2. Plan A closing should be cancelled
     *
     * @param SaleInterface $activeSale
     * @param null|SaleInterface $previousSale
     * @param DateTimeImmutable $effectiveDate
     * @param PlanInterface $newPlan
     * @return ?TargetInterface
     */
    private function tryToCancelScheduledPlanChange(
        SaleInterface $activeSale,
        ?SaleInterface $previousSale,
        DateTimeImmutable $effectiveDate,
        PlanInterface $newPlan
    ): ?TargetInterface {
        if ($previousSale === null
            || $activeSale->getTime()->format(DATE_ATOM) !== $effectiveDate->format(DATE_ATOM)
        ) {
            return null;
        }

        if ($previousSale->getPlan()?->getId() !== $newPlan->getId()) {
            return null;
        }

        $previousSale->cancelClosing();
        $this->replaceSaleInTransaction($activeSale, $previousSale, 'Failed to cancel scheduled plan change');

        return $activeSale->getTarget();
    }

    private function schedulePlanChange(
        SaleInterface $activeSale,
        CustomerInterface $customer,
        DateTimeImmutable $effectiveDate,
        PlanInterface $newPlan
    ): ?TargetInterface
    {
        $this->strategy->ensureSaleCanBeClosedForChangeAtTime($activeSale, $effectiveDate);
        $activeSale->close($effectiveDate);

        try {
            $sale = $this->connection->transaction(function () use ($activeSale, $customer, $newPlan, $effectiveDate) {
                $plan = $this->planForker->forkPlanIfRequired($newPlan, $activeSale->getCustomer());
                $sale = new Sale(null, $activeSale->getTarget(), $customer, $plan, $effectiveDate);
                $this->saleRepo->save($activeSale);
                $this->saleRepo->save($sale);

                return $sale;
            });

            return $sale->getTarget();
        } catch (InvariantException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            $this->log->error('Failed to schedule a plan change', ['exception' => $exception]);
            throw new RuntimeException('Failed to schedule a plan change');
        }

    }

    private function tryToChangeScheduledPlanChange(
        SaleInterface $activeSale,
        CustomerInterface $newCustomer,
        DateTimeImmutable $effectiveDate,
        ?PlanInterface $newPlan
    ): ?TargetInterface
    {
        $activeSaleTimeBiggerThenEffectiveTime = $activeSale->getTime()->format(DATE_ATOM) >= $effectiveDate->format(DATE_ATOM);
        $newPlanAndCustomerSameAsActiveSalePlanAndCustomer = $activeSale->getPlan()?->getId() === $newPlan?->getId()
            && $activeSale->getCustomer()->getId() === $newCustomer->getId();

        if ($activeSaleTimeBiggerThenEffectiveTime || $newPlanAndCustomerSameAsActiveSalePlanAndCustomer) {
            $newSale = new Sale(null, $activeSale->getTarget(), $activeSale->getCustomer(), $newPlan, $effectiveDate);
            $this->replaceSaleInTransaction($activeSale, $newSale, 'Failed to change scheduled plan change');

            return $newSale->getTarget();
        }

        return null;
    }

    private function getTarget(Command $command): TargetInterface
    {
        $spec = (new Specification)->where([
            'type' => $command->type,
            'name' => $command->name,
        ]);
        $target = $this->targetRepo->findOne($spec);

        if ($target === false) {
            throw new ConstraintException('Target must exist to change its plan');
        }
        if ($command->checkBelonging()) {
            $this->ensureBelongs($target, $command->customer);
        }

        return $target;
    }

    private function ensureBelongs(TargetInterface $target, CustomerInterface $customer): void
    {
        $sales = $this->saleRepo->findAll((new Specification)->where([
            'seller-id' => $customer->getSeller()->getId(),
            'target-id' => $target->getId(),
        ]));
        if (!empty($sales) && reset($sales)->getCustomer()->getId() !== $customer->getId()) {
            throw new NotAuthorizedException('The target belongs to other client');
        }
    }

    /**
     * @param TargetInterface $target
     * @param Customer $customer
     * @param DateTimeImmutable $time the sale is active at
     * @return SaleInterface|null
     */
    private function findActiveSale(TargetInterface $target, Customer $customer, DateTimeImmutable $time): ?SaleInterface
    {
        return $this->activeSaleFinder->__invoke($target, $customer, $time);
    }

    private function truncateToMonth(DateTimeImmutable $time): DateTimeImmutable
    {
        return $time->modify('first day of this month midnight');
    }

    private function ensureNotHappeningInPast(DateTimeImmutable $time, ?DateTimeImmutable $wallTime = null): void
    {
        if ($this->user->can('sale.update')) {
            $currentTime = $this->truncateToMonth($wallTime ?? $this->currentTime);
        } else {
            $currentTime = $this->truncateToMonth($this->currentTime);
        }

        if ($this->truncateToMonth($time) < $currentTime) {
            throw new ConstraintException('Plan can not be changed in past');
        }
    }

    /**
     * @param SaleInterface $sale1 sale that will be destroyed
     * @param SaleInterface $sale2 sale the will put instead of it
     * @param string $errorMessage error message for the RuntimeException that describes an error
     * @throws InvariantException when save failed due to business limitations
     * @throws RuntimeException when save failed for other reasons
     */
    private function replaceSaleInTransaction(SaleInterface $sale1, SaleInterface $sale2, string $errorMessage): void
    {
        try {
            $this->connection->transaction(function () use ($sale1, $sale2) {
                $this->saleRepo->delete($sale1);
                $this->saleRepo->save($sale2);
            });
        } catch (InvariantException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            $this->log->error($errorMessage, ['exception' => $exception]);
            throw new RuntimeException($errorMessage);
        }
    }
}
