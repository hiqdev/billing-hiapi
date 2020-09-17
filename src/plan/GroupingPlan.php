<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiqdev\billing\mrdp\Target\Tariff\TariffTarget;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\TargetInterface;

class GroupingPlan extends Plan
{
    public function convertToTarget(): TargetInterface
    {
        return new TariffTarget($this->getId(), 'tariff', $this->getName());
    }
}
