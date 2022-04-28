<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\DataMapper\Attribute\UuidAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribute\StringAttribute;

class TargetAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
            'type'      => StringAttribute::class,
            'name'      => StringAttribute::class,
            'label'     => StringAttribute::class,
            'remoteid'  => UuidAttribute::class,
        ];
    }

    public function relations()
    {
        return [
        ];
    }
}
