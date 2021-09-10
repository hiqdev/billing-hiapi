<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan;

use hiqdev\php\billing\plan\Plan;

class PlanReadModel extends Plan
{
    public array $customAttributes = [];
}
