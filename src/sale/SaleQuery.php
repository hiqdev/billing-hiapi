<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiqdev\billing\hiapi\models\Sale;
use hiqdev\billing\mrdp\Infrastructure\Database\Condition\Auth\AuthCondition;

class SaleQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Sale::class;

    protected function attributesMap()
    {
        return [
            'id' => 'zs.obj_id',
            'target' => [
                'id' => 'zs.object_id',
                'type' => 'oc.name',
            ],
            'customer' => [
                'id' => 'zc.obj_id',
                'login' => 'zc.login',
                'seller' => [
                    'id' => 'cr.obj_id',
                    'login' => 'cr.login',
                ],
            ],
            'seller' => [
                'id' => 'zs.seller_id',
            ],
            'plan' => [
                'id' => 'zs.tariff_id',
                'name' => 'zt.tariff',
            ],
            'time' => 'zs.time',
        ];
    }

    public function initFrom()
    {
        return $this->from('zsale   zs')
            ->leftJoin('obj         so', 'so.obj_id = zs.object_id')
            ->leftJoin('zref        oc', 'oc.obj_id = so.class_id')
            ->leftJoin('ztariff     zt', 'zt.obj_id = zs.tariff_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = zs.buyer_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id');
    }

    public function getFields()
    {
        return array_merge(parent::getFields(), [
            AuthCondition::byColumn('zs.buyer_id'),
        ]);
    }
}
