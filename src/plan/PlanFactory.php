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

use hiqdev\php\billing\plan\PlanCreationDto;

class PlanFactory extends \hiqdev\php\billing\plan\PlanFactory
{
    public function create(PlanCreationDto $dto)
    {
        return $this->createAnyPlan($dto, $dto->is_grouping ? GroupingPlan::class : null);
    }
}
