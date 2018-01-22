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

use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\FloatAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

class Quantity extends AbstractModel
{
    public function attributes()
    {
        return [
            'quantity' => FloatAttribute::class,
            'unit' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
