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
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

/**
 * Bill Model.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Bill extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'name' => StringAttribute::class,
            'time' => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'quantity' => Quantity::class,
            'sum' => Money::class,
            'type' => Type::class,
            'customer' => Customer::class,
            'target' => Target::class,
        ];
    }
}
