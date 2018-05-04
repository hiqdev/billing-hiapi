<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\models;

use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\DateTimeAttribute;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;

class Sale extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'time' => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'target' => Target::class,
            'seller' => Customer::class,
            'customer' => Customer::class,
            'plan' => Plan::class,
        ];
    }
}
