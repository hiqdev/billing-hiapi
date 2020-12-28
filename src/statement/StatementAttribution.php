<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\statement;

use hiqdev\billing\hiapi\vo\MoneyAttribution;
use hiqdev\DataMapper\Attribute\DateTimeAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class StatementAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'time'      => DateTimeAttribute::class,
            'period'    => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'balance'   => MoneyAttribution::class,
        ];
    }
}
