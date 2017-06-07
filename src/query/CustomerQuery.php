<?php

namespace hiqdev\billing\hiapi\query;

use hiapi\query\Field;
use hiqdev\billing\hiapi\models\Customer;

class CustomerQuery extends \hiapi\query\Query
{
    public function getFields()
    {
        return $this->fieldFactory->createByModelAttributes(new Customer(), [
            'id' => 'zc.obj_id',
            'login' => 'zc.login',
            'seller-id' => 'cr.obj_id',
            'seller-login' => 'zc.login',
        ]);
    }

    public function initSelect()
    {
        return $this->selectByFields($this->getFields())
            ->from('zclient         zc')
            ->leftJoin('zclient     cr', 'cr.obj_id = zc.seller_id');
    }

    /**
     * @param Field[] $fields
     * @return $this
     */
    protected function selectByFields($fields)
    {
        foreach ($fields as $field) {
            if ($field->canBeSelected()) {
                $this->addSelect($field->getSql() . ' as ' . $field->getName());
            }
        }

        return $this;
    }
}
