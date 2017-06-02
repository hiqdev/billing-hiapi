<?php

namespace hiqdev\billing\hiapi\query;

class CustomerQuery extends \hiapi\query\Query
{
    public function initSelect()
    {
        return $this->select(['zc.obj_id as id', 'zc.login'])
            ->from('zclient zc')
        ;
    }
}
