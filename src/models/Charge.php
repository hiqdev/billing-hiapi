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
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

class Charge extends AbstractModel
{
    public function attributes()
    {
        return [
            'id'        => IntegerAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'action'    => Action::class,
            'price'     => Price::class,
            'usage'     => Quantity::class,
            'sum'       => Money::class,
            'bill'      => Bill::class,
        ];
    }
}
