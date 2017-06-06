<?php

namespace hiqdev\billing\hiapi\query;

class BillQuery extends \hiapi\query\Query
{
    public function initSelect()
    {
        return $this->select([
                'zb.obj_id          AS id',
                'zb.time            AS time',
                'zb.quantity        AS "quantity-quantity"',
                'zb.sum             AS "sum-amount"',
                'cu.name            AS "sum-currency"',
                'bt.name            AS "type-name"',
                'zc.obj_id          AS "customer.id"',
                'zc.login           AS "customer-login"',
                'cr.obj_id          AS "customer-seller-id"',
                'cr.login           AS "customer-seller-login"',
                'zb.object_id       AS "target-id"',
                'oc.name            AS "target-type"',
            ])
            ->from('zbill           zb')
            ->leftJoin('zref        bt', 'bt.obj_id = zb.type_id')
            ->leftJoin('purse       zp', 'zp.obj_id = zb.purse_id')
            ->leftJoin('zclient     zc', 'zc.obj_id = zp.client_id')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id')
            ->leftJoin('zref        cu', 'cu.obj_id = zp.currency_id')
            ->leftJoin('obj         zo', 'zo.obj_id = zb.object_id')
            ->leftJoin('zref        oc', 'oc.obj_id = zo.class_id')
        ;
    }
}
