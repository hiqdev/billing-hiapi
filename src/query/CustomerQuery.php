<?php

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
            ]
        ];
    }

    public function initSelect()
    {
        return $this->selectByFields($this->getFields())
            ->from('zclient         zc')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id');
    }
}
