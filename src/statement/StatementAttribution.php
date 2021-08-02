<?php
declare(strict_types=1);

/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\statement;

use hiqdev\billing\hiapi\customer\CustomerAttribution;
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
            'month'     => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'customer'  => CustomerAttribution::class,
            'balance'   => MoneyAttribution::class,
            'total'     => MoneyAttribution::class,
            'payment'   => MoneyAttribution::class,
            'amount'    => MoneyAttribution::class,
        ];
    }
}
