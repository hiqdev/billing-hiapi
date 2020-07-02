<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\billing\hiapi\plan\PlanAttribution;
use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\vo\MoneyAttribution;
use hiqdev\billing\hiapi\vo\QuantityAttribution;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribute\StringAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;
use hiqdev\php\units\Unit;

class PriceAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'data' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'plan' => PlanAttribution::class,
            'target' => TargetAttribution::class,
            'type' => TypeAttribution::class,
            'unit' => Unit::class,
            'price' => MoneyAttribution::class,
            'prepaid' => QuantityAttribution::class,
        ];
    }
}
