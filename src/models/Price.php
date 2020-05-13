<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\units\Unit;
use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

/**
 * Class Price.
 *
 * @property Target target
 */
class Price extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'data' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'plan' => Plan::class,
            'target' => Target::class,
            'type' => Type::class,
            'unit' => Unit::class,
            'price' => Money::class,
            'prepaid' => Quantity::class,
        ];
    }
}
