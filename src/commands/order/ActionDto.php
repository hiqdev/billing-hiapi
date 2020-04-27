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

use hiqdev\yii\DataMapper\query\attributes\validators\NestedModelValidator;
use yii\base\Model;

class ActionDto extends Model
{
    public $type;

    public $target;

    public $quantity;

    public function rules()
    {
        return [
            [['type', 'target', 'quantity'], 'required'],
            [['target'], NestedModelValidator::class, 'modelClass' => TargetDto::class],
            [['quantity'], NestedModelValidator::class, 'modelClass' => QuantityDto::class],
            [['type'], 'string'],
        ];
    }
}
