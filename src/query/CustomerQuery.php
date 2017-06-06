<?php

namespace hiqdev\billing\hiapi\query;

class CustomerQuery extends \hiapi\query\Query
{
    public function initSelect()
    {
        return $this->select([
                'zc.obj_id          AS id',
                'zc.login           AS login',
                'cr.obj_id          AS "seller-id"',
                'cr.login           AS "seller-login"',
            ])
            ->from('zclient         zc')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
        ;
    }
}
