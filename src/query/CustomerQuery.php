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

use hiqdev\billing\hiapi\models\Customer;

class CustomerQuery extends \hiapi\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Customer::class;

    protected function attributesMap()
    {
        return [
            'id' => 'zc.obj_id',
            'login' => 'zc.login',
            'seller' => [
                'id' => 'cr.obj_id',
                'login' => 'cr.login',
            ],
        ];
    }

    public function initFrom()
    {
        return $this->from('zclient zc')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id');
    }
}
