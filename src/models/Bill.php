<?php

namespace hiqdev\billing\hiapi\models;

use hiapi\query\attributes\DateTimeAttribute;
use hiapi\query\attributes\IntegerAttribute;
use hiapi\query\attributes\StringAttribute;

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
