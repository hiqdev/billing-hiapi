<?php

namespace hiqdev\billing\hiapi\plan;

use hiqdev\billing\hiapi\target\tariff\TariffTarget;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\TargetInterface;

class GroupingPlan extends Plan
{
    public function convertToTarget(): TargetInterface
    {
        return new TariffTarget($this->getId(), 'tariff');
    }
}
