<?php

namespace hiqdev\billing\hiapi\models;

use hiapi\query\types\IntegerAttribute;
use hiapi\query\types\StringAttribute;

class Customer extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'login' => StringAttribute::class,
            'type' => StringAttribute::class
        ];
    }

    public function relations()
    {
        return [
            'seller' => self::class
        ];
    }
}
