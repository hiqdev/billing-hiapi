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
use hiqdev\DataMapper\Attribute\StringAttribute;

class UnitAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'name' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
