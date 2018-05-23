<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\type;

use hiqdev\billing\hiapi\models\Type;
use hiqdev\yii\DataMapper\query\join\Join;
use hiqdev\yii\DataMapper\query\JoinedField;
use yii\db\Expression;

class TypeQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Type::class;

    /**
     * {@inheritdoc}
     */
    protected function attributesMap()
    {
        return [
            'id' => 'zr.obj_id',
            'name' => new Expression("pr.name || ',' || zr.name as name"),
            //                                TODO: Drop ˯˯˯˯˯˯˯˯ this after removing models
            new JoinedField('fullName', 'zh.name', $this->getModel()->getAttribute('fullName'), 'fullRef'),
        ];
    }

    public function joins()
    {
        return [
            'fullRef' => new Join('zref_h zh', 'zh.obj_id = zr.obj_id'),
        ];
    }

    public function initFrom()
    {
        return $this
            ->from('zref zr')
            ->leftJoin('zref pr', 'pr.obj_id = zr._id');
    }
}
