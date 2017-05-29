<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\Target as Entity;
use yii\db\ActiveRecord;

class Target extends ActiveRecord
{
    public static function tableName()
    {
        return 'obj';
    }

    public function getSeller()
    {
        return $this->hasOne(Customer::class, ['obj_id' => 'seller_id']);
    }

    public function getEntity()
    {
        return new Entity($this->obj_id, $this->login);
    }
}
