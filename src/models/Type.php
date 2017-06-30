<?php

namespace hiqdev\billing\hiapi\models;

use hiapi\query\attributes\IntegerAttribute;
use hiapi\query\attributes\StringAttribute;

class Type extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'name' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
