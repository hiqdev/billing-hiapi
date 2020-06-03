<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\DataMapper\Attribution\AbstractAttribution;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\action\ActionAttribution;
use hiqdev\billing\hiapi\price\PriceAttribution;
use hiqdev\billing\hiapi\vo\QuantityAttribution;
use hiqdev\billing\hiapi\vo\MoneyAttribution;
use hiqdev\billing\hiapi\bill\BillAttribution;

class ChargeAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'type'      => TypeAttribution::class,
            'target'    => TargetAttribution::class,
            'action'    => ActionAttribution::class,
            'price'     => PriceAttribution::class,
            'usage'     => QuantityAttribution::class,
            'sum'       => MoneyAttribution::class,
            'bill'      => BillAttribution::class,
            'parent'    => self::class,
        ];
    }
}
