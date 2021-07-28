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

use hiqdev\billing\hiapi\customer\CustomerAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\price\PriceAttribution;
use hiqdev\DataMapper\Attribute\BooleanAttribute;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribute\StringAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class PlanAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id'          => IntegerAttribute::class,
            'name'        => StringAttribute::class,
            'is_grouping' => BooleanAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'type' => TypeAttribution::class,
            'seller' => CustomerAttribution::class,
            'prices' => PriceAttribution::class,
        ];
    }
}
