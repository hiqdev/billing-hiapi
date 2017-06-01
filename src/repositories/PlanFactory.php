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
}
