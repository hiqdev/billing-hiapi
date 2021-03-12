<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiqdev\billing\hiapi\customer\CustomerAttribution;
use hiqdev\billing\hiapi\plan\PlanAttribution;
use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\DataMapper\Attribute\DateTimeAttribute;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class SaleAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'time' => DateTimeAttribute::class,
            'closeTime' => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'target' => TargetAttribution::class,
            'seller' => CustomerAttribution::class,
            'customer' => CustomerAttribution::class,
            'plan' => PlanAttribution::class,
        ];
    }
}
