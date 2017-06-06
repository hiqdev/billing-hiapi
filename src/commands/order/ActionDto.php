<?php

namespace hiqdev\billing\hiapi\commands\order;

use hiapi\validators\NestedModelValidator;
use yii\base\Model;

class ActionDto extends Model
{
    public $type;

    public $target;

    public $quantity;

    public function rules()
    {
        return [
            [['type', 'target', 'quantity'], 'required'],
            [['target'], NestedModelValidator::class, 'modelClass' => TargetDto::class],
            [['quantity'], NestedModelValidator::class, 'modelClass' => QuantityDto::class],
            [['type'], 'string']
        ];
    }
}
