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
        $target = $this->targetFactory->create($this->createTargetDto([
            'type'      => $command->type,
            'name'      => $command->name,
            'customer'  => $command->customer,
            'remoteid'  => $command->remoteid,
        ]));
        $this->targetRepo->save($target);
        $sale = new Sale(null, $target, $command->customer, $command->plan, $command->time);
        $this->saleRepo->save($sale);

        return $target;
    }

    protected function createTargetDto(array $data): RemoteTargetCreationDto
    {
        $dto = new RemoteTargetCreationDto();
        foreach ($data as $key => $value) {
            $dto->$key = $value;
        }

        return $dto;
    }
}
