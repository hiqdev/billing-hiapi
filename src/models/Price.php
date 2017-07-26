<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\AbstractPrice as Entity;
use hiqdev\php\units\Quantity;
use Money\Money;
use yii\db\ActiveRecord;

/**
 * Class Price
 *
 * @property Target target
 */
class Price extends ActiveRecord
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'plan' => Plan::class,
            'target' => Target::class,
            'type' => Type::class,
            'unit' => Unit::class,
            'price' => Money::class,
            'quantity' => Quantity::class,
        ];
    }
}
