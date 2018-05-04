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

use hiqdev\yii\DataMapper\commands\BaseCommand;
use hiqdev\yii\DataMapper\validators\NestedModelValidator;

class CalculateValueCommand extends BaseCommand
{
    public $items;

    public function rules()
    {
        return [
            ['items', 'required'],
            ['items', 'each', 'rule' => [NestedModelValidator::class, 'modelClass' => ActionDto::class]],
        ];
    }
}
