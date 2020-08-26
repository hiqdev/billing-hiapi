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
use hiqdev\billing\hiapi\target\RemoteTargetCreationDto;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\customer\CustomerInterface;

class Action
{
    private TargetRepositoryInterface $targetRepo;
    private TargetFactoryInterface $targetFactory;
    private SaleRepositoryInterface $saleRepo;

    public function __construct(
        TargetRepositoryInterface $targetRepo,
        TargetFactoryInterface $targetFactory,
        SaleRepositoryInterface $saleRepo
    ) {
        $this->targetRepo = $targetRepo;
        $this->targetFactory = $targetFactory;
        $this->saleRepo = $saleRepo;
    }

    public function __invoke(Command $command): Target
    {
        $target = $this->getTarget($command);
        $this->checkBelongs($target, $command->customer);
        $sale = new Sale(null, $target, $command->customer, $command->plan, $command->time);
        $this->saleRepo->save($sale);

        return $target;
    }

    private function checkBelongs(TargetInterface $target, CustomerInterface $customer)
    {
        $sales = $this->saleRepo->findAll((new Specification)->where([
            'seller-id' => $customer->getSeller()->getId(),
            'target-id' => $target->getId(),
        ]));
        if (!empty($sales) && reset($sales)->getCustomer()->getId() !== $customer->getId()) {
            throw new NotAuthorizedException('The target belongs to other client');
        }
    }

    private function getTarget(Command $command): Target
    {
        $spec = (new Specification)->where([
            'type' => $command->type,
            'name' => $command->name,
        ]);
        $target = $this->targetRepo->findOne($spec);

        return $target ?: $this->createTarget($command);
    }

    private function createTarget(Command $command): Target
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
}
