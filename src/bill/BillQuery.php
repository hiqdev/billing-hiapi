<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use hiqdev\billing\hiapi\models\Bill;

class BillQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Bill::class;

    protected function attributesMap()
    {
        return [
            'id' => 'zb.obj_id',
            'type' => [
                'id' => 'zb.type_id',
                'name' => 'bt.g2name',
            ],
            'plan' => [
                'id' => 'zb.tariff_id',
            ],
            'target' => [
                'id' => 'zb.object_id',
                'type' => 'tt.name',
                'name' => 'tj.name',
            ],
            'customer' => [
                'id' => 'zc.obj_id',
                'login' => 'zc.login',
                'seller' => [
                    'id' => 'cr.obj_id',
                    'login' => 'cr.login',
                ],
            ],
            'sum' => [
                'currency' => 'py.name',
                'amount' => 'zb.sum',
            ],
            'quantity' => [
                'unit' => 'qu.name',
                'quantity' => 'zb.quantity',
            ],
            'time' => 'zb.time',
        ];
    }

    public function initFrom()
    {
        return $this->from('zbill   zb')
            ->leftJoin('gref        bt', 'bt.obj_id = zb.type_id')
            ->leftJoin('target      tj', 'tj.obj_id = zb.object_id')
            ->leftJoin('zref        tt', 'tt.obj_id = tj.class_id')
            ->leftJoin('purse       bp', 'bp.obj_id = zb.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = bp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        py', 'py.obj_id = bp.currency_id')
            ->leftJoin('zref        qu', 'qu.obj_id = zb.unit_id');
    }
}
