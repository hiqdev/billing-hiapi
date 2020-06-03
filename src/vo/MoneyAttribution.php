<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\vo;

use hiqdev\DataMapper\Attribution\AbstractAttribution;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribute\StringAttribute;

/**
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class MoneyAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'amount' => IntegerAttribute::class,
            'currency' => StringAttribute::class, // todo: regexp validatior
        ];
    }

    public function relations()
    {
        return [];
    }
}
