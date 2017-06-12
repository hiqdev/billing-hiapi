<?php

namespace hiqdev\billing\hiapi\models;

use hiapi\query\types\IntegerAttribute;
use hiapi\query\types\StringAttribute;

class Bill extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'name' => StringAttribute::class,
            'time' => StringAttribute::class, // todo: change to time
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
