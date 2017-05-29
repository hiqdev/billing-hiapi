<?php

namespace hiqdev\billing\hiapi\models;

use Money\Currency as Entity;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord
{
    public static function tableName()
    {
        return 'zref';
    }

    public function getEntity()
    {
        return new Entity($this->obj_id, $this->name);
    }
}
