<?php

namespace hiqdev\billing\hiapi\commands\order;

use hiapi\validators\NestedModelValidator;
use yii\base\Model;

class ActionDto extends Model
{
    public $type;

    public $target;

    public $quantity;

    public function load($data, $formName = '')
    {
        if (isset($data['quantity'])) {
            $quantity = new QuantityDto();
            $quantity->load($data['quantity']);

            $data['quantity'] = $quantity;
        }

        if (isset($data['target'])) {
            $target = new TargetDto();
            $target->load($data['target']);

            $data['target'] = $target;
        }

        return $this->setAttributes($data);
    }

    public function rules()
    {
        return [
            [['type', 'target', 'quantity'], 'required'],
            [['target', 'quantity'], NestedModelValidator::class],
            [['type'], 'string']
        ];
    }
}
