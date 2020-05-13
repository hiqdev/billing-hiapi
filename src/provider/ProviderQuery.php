<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

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
