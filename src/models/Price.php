<?php

namespace hiqdev\billing\hiapi\models;

use hiqdev\php\billing\AbstractPrice as Entity;
use hiqdev\php\units\Quantity;
use Money\Money;
use yii\db\ActiveRecord;

/**
 * Class Price
 *
 * @property Target target
 */
class Price extends ActiveRecord
{
    public static function tableName()
    {
        return 'tariff_resourcez';
    }

    public static function conversions()
    {
        return [
            'id' => 'obj_id',
        ];
    }

    public function getTarget()
    {
        return $this->hasOne(Target::class, ['obj_id' => 'object_id']);
    }

    public function getType()
    {
        return $this->hasOne(Type::class, ['obj_id' => 'type_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Type::class, ['obj_id' => 'unit_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Type::class, ['obj_id' => 'currency_id']);
    }

    public function getEntity()
    {
        $unit = $this->unit->getEntity();
        $currency = $this->currency->getEntity();

        return Entity::create([
            'id'        => $this->obj_id,
            'target'    => $this->target->getEntity(),
            'type'      => $this->type->getEntity(),
            'prepaid'   => Quantity::create($unit, $this->quantity),
            'price'     => new Money($this->price, $currency),
        ]);
    }
}
