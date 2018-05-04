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
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

class Currency extends AbstractModel
{
    public function attributes()
    {
        return [
            'name' => StringAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
