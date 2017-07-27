<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\models;

use hiapi\query\attributes\IntegerAttribute;
use hiapi\query\attributes\StringAttribute;

/**
 * Class Quantity.
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
