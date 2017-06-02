<?php

namespace hiqdev\billing\hiapi\commands\order;

use hiqdev\php\units\Quantity;
use yii\base\Model;

class QuantityDto extends Model
{
    public $unit;

    public $quantity;

    public function rules()
    {
        return [
            [['unit', 'quantity'], 'required'],
            ['unit', 'string'],
            ['quantity', 'number'],
            ['unit', 'unitValidation']
        ];
    }

    public function unitValidation()
    {
        try {
            Quantity::create($this->unit, $this->quantity);
            return true;
        } catch (\Exception $exception) {
            $this->addError('unit', $exception->getMessage());
            return false;
        }
    }

    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }
}
