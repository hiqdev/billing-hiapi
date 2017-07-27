<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories;

use hiqdev\php\billing\Plan;
use ReflectionClass;

class PlanFactory
{
    protected $class = Plan::class;

    public function createPrototype()
    {
        return (new ReflectionClass($this->class))->newInstanceWithoutConstructor();
    }

    public function create(PlanCreationDto $dto)
    {
        return new Plan($dto->id, $dto->name, $dto->seller, $dto->prices);
    }
}
