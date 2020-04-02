<?php

namespace hiqdev\billing\hiapi\method;

use hiqdev\php\billing\method\Method;
use hiqdev\yii\DataMapper\query\Query;

class MethodQuery extends Query
{
    /**
     * @var string
     */
    protected $modelClass = Method::class;

    protected function attributesMap()
    {
        return [
        ];
    }

    public function initFrom()
    {
        return $this->from('zmethod   zm')->leftJoin('', '');
    }
}
