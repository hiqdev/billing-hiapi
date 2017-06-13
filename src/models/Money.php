<?php

namespace hiqdev\billing\hiapi\models;

use hiapi\query\attributes\IntegerAttribute;
use hiapi\query\attributes\StringAttribute;

/**
 * Class Quantity
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Money extends AbstractModel
{
    public function attributes()
    {
        return [
            'amount' => IntegerAttribute::class,
            'currency' => StringAttribute::class, // todo: regexp validatior
        ];
    }

    public function relations()
    {
        return [];
    }
}
