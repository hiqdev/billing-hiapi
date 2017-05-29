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

    public static function conversions()
    {
        return [
            'id' => 'obj_id',
        ];
    }

    public function getSeller()
    {
        return $this->hasOne(Customer::class, ['obj_id' => 'client_id']);
    }

    public function getEntity()
    {
        return new Entity($this->obj_id, $this->name, $this->seller->getEntity());
    }
}
