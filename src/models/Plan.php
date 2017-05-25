<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\Plan as Entity;
use yii\db\ActiveRecord;

class Plan extends ActiveRecord
{
    public static function tableName()
    {
        return 'tariff';
    }
}
