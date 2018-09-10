<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\billing\hiapi\models\Charge;

class ChargeQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Charge::class;

    protected function attributesMap()
    {
        return [
            'id' => 'zh.id',
            'bill' => [
                'id' => 'zh.bill_id',
            ],
            'action' => [
                'id' => 'zh.action_id',
                'type' => [
                    'name' => 'at.g2name',
                ],
                'target' => [
                    'id' => 'ha.object_id',
                    'type' => 'tt.name',
                ],
                'customer' => [
                    'id' => 'zc.obj_id',
                    'login' => 'zc.login',
                    'seller' => [
                        'id' => 'cr.obj_id',
                        'login' => 'cr.login',
                    ],
                ],
                'quantity' => [
                    'unit' => "hu.parent",
                    'quantity' => 'ha.amount',
                ],
                'time' => 'ha.time',
            ],
            'sum' => [
                'currency' => 'py.name',
                'amount' => 'zh.sum',
            ],
            'usage' => [
                'unit' => 'hu.name',
                'quantity' => 'zh.quantity',
            ],
        ];
    }

    public function initFrom()
    {
        return $this->from('zcharge zh')
            ->leftJoin('purse       hp', 'hp.obj_id = zh.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = hp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        py', 'py.obj_id = hp.currency_id')
            ->leftJoin('unit        hu', 'hu.obj_id = zh.unit_id')
            ->leftJoin('action      ha', 'ha.id = zh.action_id')
            ->leftJoin('obj         tj', 'tj.obj_id = zh.object_id')
            ->leftJoin('zref        tt', 'tt.obj_id = tj.class_id')
            ->leftJoin('gref        at', 'at.obj_id = ha.type_id');
    }
}
