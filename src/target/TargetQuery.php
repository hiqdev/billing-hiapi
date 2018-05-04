<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\billing\hiapi\models\Target;
use yii\db\Expression;

class TargetQuery extends \hiqdev\yii\DataMapper\query\Query
{
    protected $modelClass = Target::class;

    /**
     * @return array
     */
    protected function attributesMap()
    {
        return [
            'id' => 'o.obj_id',
            'type' => new Expression("
                t.name || 
                CASE WHEN tt.name IS NOT NULL THEN
                    '.' || tt.name
                ELSE
                    ''
                END as type
            "),
            'name' => new Expression("
                coalesce(d.name, tr.name, '') as name
            "),
        ];
    }

    public function initFrom()
    {
        return $this->from('obj   o')
                ->leftJoin('zref   t',  't.obj_id  = o.class_id')
                ->leftJoin('device d',  'd.obj_id  = o.obj_id')
                ->leftJoin('tariff tr', 'tr.obj_id = o.obj_id')
                ->leftJoin('zref   tt', 'tt.obj_id = coalesce(d.type_id, tr.type_id)');
    }
}
