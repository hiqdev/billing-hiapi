<?php

namespace hiqdev\billing\hiapi\commands\order;

use hiapi\commands\BaseCommand;
use hiapi\validators\NestedModelValidator;

class CalculateValueCommand extends BaseCommand
{
    public $items;

    public function load($data, $formName = '')
    {
        if (isset($data['items'])) {
            $items = [];
            foreach ($data['items'] as $item) {
                $items[] = $action = new ActionDto();
                $action->load($item);
            }
            $data['items'] = $items;
        }

        return $this->setAttributes($data);
    }

    public function rules()
    {
        return [
            ['items', 'required'],
            ['items', 'each', 'rule' => [NestedModelValidator::class]],
        ];
    }
}
