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
                'name' => 'tt.name',
            ],
            'seller' => [
                'id' => 'zc.obj_id',
                'login' => 'zc.login',
                'seller' => [
                    'id' => 'cr.obj_id',
                    'login' => 'cr.login',
                ],
            ],
            'quantity' => [
                'unit' => 'qu.name',
                'quantity' => 'zb.quantity',
            ],
        ];
    }

    public function initFrom()
    {
        return $this->from('zbill   zb')
            ->leftJoin('zref        tt', 'tt.obj_id = zb.type_id')
            ->leftJoin('purse       bp', 'bp.obj_id = zb.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = bp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        qu', 'qu.obj_id = zb.unit_id');
    }
}
