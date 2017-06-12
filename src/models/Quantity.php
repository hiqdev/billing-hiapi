<?php

namespace hiqdev\billing\hiapi\models;


use hiapi\query\types\FloatAttribute;

class Quantity extends AbstractModel
{
    public function attributes()
    {
        return [
            'quantity' => FloatAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
