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

namespace hiqdev\billing\hiapi\target\Purchase;

use hiapi\exceptions\NotAuthorizedException;
use hiapi\legacy\lib\billing\plan\Forker\PlanForkerInterface;
use hiqdev\billing\hiapi\target\RemoteTargetCreationDto;
use hiqdev\billing\hiapi\tools\PermissionCheckerInterface;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\Exception\ConstraintException;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\php\billing\target\TargetWasCreated;
use hiqdev\php\billing\usage\Usage;
use hiqdev\php\billing\usage\UsageRecorderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use DateTimeImmutable;

class Action
{
    protected bool $targetWasCreated = false;
    private TargetRepositoryInterface $targetRepo;
    private TargetFactoryInterface $targetFactory;
    private SaleRepositoryInterface $saleRepo;
    private PlanForkerInterface $planForker;
    private PermissionCheckerInterface $permissionChecker;
    private UsageRecorderInterface $usageRecorder;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        TargetRepositoryInterface $targetRepo,
        TargetFactoryInterface $targetFactory,
        SaleRepositoryInterface $saleRepo,
        PlanForkerInterface $planForker,
        PermissionCheckerInterface $permissionChecker,
        UsageRecorderInterface $usageRecorder,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->targetRepo = $targetRepo;
        $this->targetFactory = $targetFactory;
        $this->saleRepo = $saleRepo;
        $this->planForker = $planForker;
        $this->permissionChecker = $permissionChecker;
        $this->usageRecorder = $usageRecorder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Command $command): TargetInterface
    {
        $this->targetWasCreated = false;
        $this->permissionChecker->ensureCustomerCan($command->customer, 'have-goods');
        $target = $this->getTarget($command);
        $plan = $this->planForker->forkPlanIfRequired($command->plan, $command->customer);
        $sale = new Sale(null, $target, $command->customer, $plan, $command->time);

        $saleId = $this->saleRepo->findId($sale);
        if ($saleId !== null) {
            return $target;
        }

        $this->saleRepo->save($sale);

        if ($this->targetWasCreated) {
            $this->eventDispatcher->dispatch(TargetWasCreated::occurred($target));
        }
        $this->saveInitialUses($sale, $command->initial_uses);

        return $target;
    }

    private function getTarget(Command $command): TargetInterface
    {
        $spec = (new Specification)->where([
            'type' => $command->type,
            'name' => $command->name,
        ]);
        $target = $this->targetRepo->findOne($spec);

        if ($target === false) {
            $spec = (new Specification)->where([
                'type' => $command->type,
                'remoteid' => $command->remoteid,
                'customer_id' => $command->customer->getId(),
            ]);
            $target = $this->targetRepo->findByRemoteid($spec);
        }

        if ($target === false) {
            return $this->createTarget($command);
        }
        $this->ensureBelongs($target, $command->customer, $command->time);

        return $target;
    }

    private function ensureBelongs(TargetInterface $target, CustomerInterface $customer, DateTimeImmutable $time = null): void
    {
        $sales = $this->saleRepo->findAll((new Specification)->where([
            'seller-id' => $customer->getSeller()->getId(),
            'target-id' => $target->getId(),
        ]));
        if (!empty($sales) && reset($sales)->getCustomer()->getId() !== $customer->getId()) {
            throw new NotAuthorizedException('The target belongs to other client');
        }
    }

    private function createTarget(Command $command): TargetInterface
    {
        $dto = new RemoteTargetCreationDto();
        $dto->type = $command->type;
        $dto->name = $command->name;
        $dto->customer = $command->customer;
        $dto->remoteid = $command->remoteid;

        $target = $this->targetFactory->create($dto);
        $this->targetRepo->save($target);
        $this->targetWasCreated = true;

        return $target;
    }

    /**
     * @param PlanInterface $plan
     * @param list<InitialUse> $initialUses
     */
    private function saveInitialUses(SaleInterface $sale, array $initialUses): void
    {
        $usedTypes = array_map(static fn (InitialUse $use) => $use->type->getName(), $initialUses);
        if ($usedTypes !== array_unique($usedTypes)) {
            throw new ConstraintException('The same Initial Use type must be listed only once');
        }

        $plan = $sale->getPlan();
        foreach ($initialUses as $use) {
            foreach ($plan->getPrices() as $price) {
                if ($price->getType()->matches($use->type)) {
                    $use->type = $price->getType(); // In order to have Type with ID
                    continue 2;
                }
            }
            throw new ConstraintException('The Initial Use type must be within Plan Prices types');
        }

        foreach ($initialUses as $use) {
            $this->usageRecorder->record(
                new Usage($sale->getTarget(), $sale->getTime(), $use->type, $use->quantity)
            );
        }
    }

    private function getActiveSales(TargetInterface $target, CustomerInterface $customer, DateTimeImmutable $time = null): ?array
    {
        $_sales = $this->saleRepo->findAll((new Specification)->where(array_filter([
            'seller-id' => $customer->getSeller()->getId(),
            'target-id' => $target->getId(),
        ])));
        if ($time === null || empty($_sales)) {
            return $_sales;
        }

        foreach ($_sales as $sale) {
            if ($sale->getCloseTime() !== null && $sale->getCloseTime() <= $time) {
                continue;
            }

            $sales[] = $sale;
        }

        return $sales;
    }
}
