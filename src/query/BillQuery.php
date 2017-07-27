<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\query;

use hiqdev\billing\hiapi\models\Bill;
use yii\db\Expression;

class BillQuery extends \hiapi\query\Query
{
    protected $modelClass = Bill::class;

    /**
     * @return array
     */
    protected function attributesMap()
    {
        return [
            'id' => 'zb.obj_id',
            'time' => new Expression("date_trunc('second', zb.time) as time"),
            'quantity' => [
                'quantity' => 'zb.quantity',
            ],
            'sum' => [
                'currency' => 'cu.name',
                'amount' => 'zb.sum',
            ],
            'type' => [
                'name' => 'bt.name',
            ],
            'customer' => [
                'id' => 'zc.obj_id',
                'login' => 'zc.login',
                'seller' => [
                    'id' => 'cr.obj_id',
                    'login' => 'cr.login',
                ],
            ],
            'target' => [
                'id' => 'zb.object_id',
                'type' => 'oc.name',
            ],
        ];
    }

    public function initFrom()
    {
        return $this->from('zbill   zb')
            ->leftJoin('zref        bt', 'bt.obj_id = zb.type_id')
            ->leftJoin('purse       zp', 'zp.obj_id = zb.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = zp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        cu', 'cu.obj_id = zp.currency_id')
            ->leftJoin('obj         zo', 'zo.obj_id = zb.object_id')
            ->leftJoin('zref        oc', 'oc.obj_id = zo.class_id');
    }
}
