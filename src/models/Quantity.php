<?php

namespace hiqdev\billing\hiapi\models;


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

    }
}
