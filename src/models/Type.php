<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\Type as Entity;
use yii\db\ActiveRecord;

class Type extends ActiveRecord
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
