<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use hiqdev\billing\hiapi\customer\CustomerAttribution;
use hiqdev\billing\hiapi\plan\PlanAttribution;
use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\vo\MoneyAttribution;
use hiqdev\billing\hiapi\vo\QuantityAttribution;
use hiqdev\DataMapper\Attribute\DateTimeAttribute;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribute\StringAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class BillAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
            'time'      => DateTimeAttribute::class,
            'from'      => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'type'      => TypeAttribution::class,
            'target'    => TargetAttribution::class,
            'customer'  => CustomerAttribution::class,
            'plan'      => PlanAttribution::class,
            'sum'       => MoneyAttribution::class,
            'quantity'  => QuantityAttribution::class,
        ];
    }
}
