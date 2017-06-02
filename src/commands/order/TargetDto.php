<?php

namespace hiqdev\billing\hiapi\commands\order;

use yii\base\Model;

class TargetDto extends Model
{
    public $id;

    public $type;

    public function rules()
    {
        return [
            [['id', 'type'], 'required'],
            [['id'], 'integer'],
            [['type'], 'string'],
        ];
    }

    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }
}
