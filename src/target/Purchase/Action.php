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
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;

class Action
{
    private TargetRepositoryInterface $targetRepo;
    private TargetFactoryInterface $targetFactory;
    private SaleRepositoryInterface $saleRepo;
    private PlanForkerInterface $planForker;

    public function __construct(
        TargetRepositoryInterface $targetRepo,
        TargetFactoryInterface $targetFactory,
        SaleRepositoryInterface $saleRepo,
        PlanForkerInterface $planForker
    ) {
        $this->targetRepo = $targetRepo;
        $this->targetFactory = $targetFactory;
        $this->saleRepo = $saleRepo;
        $this->planForker = $planForker;
    }

    public function __invoke(Command $command): TargetInterface
    {
        $target = $this->getTarget($command);
        $plan = $this->forkPlanIfRequired($command->plan, $command->customer);
        $sale = new Sale(null, $target, $command->customer, $plan, $command->time);
        $saleExists = $this->saleRepo->findId($sale);
        if (!$saleExists) {
            $this->saleRepo->save($sale);
        }

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
            return $this->createTarget($command);
        }
        $this->ensureBelongs($target, $command->customer);

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

    private function createTarget(Command $command): TargetInterface
    {
        $dto = new RemoteTargetCreationDto();
        $dto->type = $command->type;
        $dto->name = $command->name;
        $dto->customer = $command->customer;
        $dto->remoteid = $command->remoteid;

        $target = $this->targetFactory->create($dto);
        $this->targetRepo->save($target);

        return $target;
    }

    private function forkPlanIfRequired(PlanInterface $plan, CustomerInterface $customer): PlanInterface
    {
        if ($this->planForker->checkMustBeForkedOnPurchase($plan)) {
            return $this->planForker->forkPlan($plan, $customer);
        }

        return $plan;
    }
}
