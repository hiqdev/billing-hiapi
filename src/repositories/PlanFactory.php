<?php


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
