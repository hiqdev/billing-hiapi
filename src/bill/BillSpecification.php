<?php

namespace hiqdev\billing\hiapi\bill;

use hiqdev\billing\mrdp\Infrastructure\Database\Condition\Auth\AuthRule;
use hiqdev\billing\mrdp\Infrastructure\Database\Condition\Auth\AuthCondition;
use hiqdev\yii\DataMapper\query\Specification;

class BillSpecification extends Specification
{
    public function authCond(AuthRule $authCond): self
    {
        // TODO: use andWhere. Does not work because of QueryBuilder::flattenArray()
        $this->where[AuthCondition::class] = $authCond;

        return $this;
    }
}
