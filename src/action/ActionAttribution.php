<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action;

use hiqdev\billing\hiapi\customer\CustomerAttribution;
use hiqdev\billing\hiapi\sale\SaleAttribution;
use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\vo\QuantityAttribution;
use hiqdev\DataMapper\Attribute\DateTimeAttribute;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class ActionAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
            'time'      => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'type'      => TypeAttribution::class,
            'target'    => TargetAttribution::class,
            'quantity'  => QuantityAttribution::class,
            'customer'  => CustomerAttribution::class,
            'sale'      => SaleAttribution::class,
        ];
    }
}
