<?php

namespace hiqdev\billing\hiapi\plan;

use hiapi\query\FieldInterface;
use yii\db\Expression;

class AvailableForField implements FieldInterface
{
    public $name = 'available_for';

    public function canBeSelected()
    {
        return false;
    }

    public function isApplicable($key)
    {
        return strcasecmp($this->name, $key) === 0;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function buildCondition($value)
    {
        $params = [];
        if ($value['client_id']) {
            $params[':available_for_client_id'] = $value['client_id'];
            $ids_sql = "
                SELECT      tariff_id
                FROM        client2tariff
                WHERE       client_id=:available_for_client_id
            ";
        } else {
            $params[':available_for_seller'] = $value['seller'];
            $ids_sql = "
                SELECT      dst_id
                FROM        tie
                WHERE       src_id=client_id(:available_for_seller)
                        AND tag_id=type_id('tariff')
            ";
        }

        return new Expression("zt.obj_id IN ($ids_sql)", $params);
    }
}
