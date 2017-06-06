<?php

namespace hiqdev\billing\hiapi\commands\order;

use hiapi\commands\BaseCommand;
use hiapi\validators\NestedModelValidator;

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
