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
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\DateTimeAttribute;

class Action extends AbstractModel
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
            'time'      => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'type'      => Type::class,
            'target'    => Target::class,
            'quantity'  => Quantity::class,
            'customer'  => Customer::class,
            'sale'      => Sale::class,
        ];
    }
}
