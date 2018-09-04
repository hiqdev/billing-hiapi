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
                    'name' => 'at.name',
                ],
                'customer' => [
                    'id' => 'zc.obj_id',
                    'login' => 'zc.login',
                    'seller' => [
                        'id' => 'cr.obj_id',
                        'login' => 'cr.login',
                    ],
                ],
            ],
            'usage' => [
                'unit' => 'qu.name',
                'quantity' => 'zh.quantity',
            ],
        ];
    }

    public function initFrom()
    {
        return $this->from('zcharge zh')
            ->leftJoin('purse       bp', 'bp.obj_id = zh.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = bp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        qu', 'qu.obj_id = zh.unit_id')
            ->leftJoin('action      ha', 'ha.id = zh.action_id')
            ->leftJoin('zref        at', 'at.obj_id = ha.type_id');
    }
}
