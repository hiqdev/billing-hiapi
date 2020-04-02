<?php

namespace hiqdev\billing\hiapi\provider;

use hiqdev\php\billing\method\Method;
use hiqdev\yii\DataMapper\query\Query;

class ProviderQuery extends Query
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
