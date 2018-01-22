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
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

class Target extends AbstractModel
{
    public function attributes()
    {
        return [
            'id'    => IntegerAttribute::class,
            'name'  => StringAttribute::class,
            'type'  => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [
        ];
    }
}
