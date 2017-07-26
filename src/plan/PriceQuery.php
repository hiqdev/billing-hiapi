<?php

namespace hiqdev\billing\hiapi\plan;

use hiqdev\billing\hiapi\models\Price;

class PriceQuery extends \hiapi\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Price::class;

    protected function attributesMap()
    {
        return [
            'id' => 'tr.id',
            'plan' => [
                'id' => 'tr.tariff_id',
            ],
            'target' => [
                'id' => 'tr.object_id',
                'type' => 'oc.name',
            ],
            'type' => [
                'name' => 'rt.name',
            ],
            'price' => [
                'currency' => 'cu.name',
                'amount' => 'tr.price',
            ],
            'quantity' => [
                'unit' => 'tu.name',
                'quantity' => 'tr.quantity',
            ],
            'data' => 'tr.data',
        ];
    }

    public function initFrom()
    {
        return $this
            ->from('tariff_resourcez    tr')
            ->leftJoin('zref            rt', 'rt.obj_id = tr.type_id')
            ->leftJoin('zref            tu', 'tu.obj_id = tr.unit_id')
            ->leftJoin('zref            cu', 'cu.obj_id = tr.currency_id')
            ->leftJoin('obj             zo', 'zo.obj_id = zb.object_id')
            ->leftJoin('zref            oc', 'oc.obj_id = zo.class_id')
        ;
    }
}
