<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\billing\hiapi\models\Price;

class PriceQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Price::class;

    protected function attributesMap()
    {
        return [
            'id' => 'zp.id',
            'plan' => [
                'id' => 'zp.tariff_id',
            ],
            'target' => [
                'id' => 'zp.object_id',
                'type' => 'oc.name',
            ],
            'type' => [
                'id' => 'zp.type_id',
                'name' => 'rt.name',
            ],
            'price' => [
                'currency' => 'cu.name',
                'amount' => 'zp.price',
            ],
            'prepaid' => [
                'unit' => 'tu.name',
                'quantity' => 'zp.quantity',
            ],
            'data' => 'zp.data',
        ];
    }

    public function initFrom()
    {
        return $this
            ->from('uprice              zp')
            ->leftJoin('zref            rt', 'rt.obj_id = zp.type_id')
            ->leftJoin('zref            tu', 'tu.obj_id = zp.unit_id')
            ->leftJoin('zref            cu', 'cu.obj_id = zp.currency_id')
            ->leftJoin('obj             zo', 'zo.obj_id = zp.object_id')
            ->leftJoin('zref            oc', 'oc.obj_id = zo.class_id');
    }
}
