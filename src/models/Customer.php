<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\Customer as Entity;
use yii\db\ActiveRecord;

class Customer extends ActiveRecord
{
    public static function tableName()
    {
        return 'zclient';
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
