<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\commands\order;

use yii\base\Model;

class TargetDto extends Model
{
    public $id;

    public $type;

    public function rules()
    {
        return [
            [['id', 'type'], 'required'],
            [['id'], 'integer'],
            [['type'], 'string'],
        ];
    }

    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }
}
