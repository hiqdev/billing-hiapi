<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target\Purchase;

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
    /**
     * @var TargetRepositoryInterface
     */
    private $targetRepo;

    /**
     * @var TargetFactoryInterface
     */
    private $targetFactory;

    /**
     * @var SaleRepositoryInterface
     */
    private $saleRepo;

    public function __construct(TargetRepositoryInterface $targetRepo, TargetFactoryInterface $targetFactory, SaleRepositoryInterface $saleRepo)
    {
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
            throw new \Exception('the target belongs to other user');
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
        $target = $this->targetFactory->create($this->createTargetDto([
            'type'      => $command->type,
            'name'      => $command->name,
            'customer'  => $command->customer,
            'remoteid'  => $command->remoteid,
        ]));
        $this->targetRepo->save($target);

        return $target;
    }

    private function createTargetDto(array $data): RemoteTargetCreationDto
    {
        $dto = new RemoteTargetCreationDto();
        foreach ($data as $key => $value) {
            $dto->$key = $value;
        }

        return $dto;
    }
}
