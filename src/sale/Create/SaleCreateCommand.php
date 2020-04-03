<?php

namespace hiqdev\billing\hiapi\sale\Create;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;

class SaleCreateCommand extends BaseCommand
{
    public $customer_id;

    public $plan_id;

    public $target_id;

    public $time;

    public $customer;

    public $plan;

    public $target;

    public function rules(): array
    {
        return [
            [['customer_id'], IdValidator::class],

            [['plan_id'], IdValidator::class],
            [['plan_id'], 'required'],

            [['target_id'], IdValidator::class],
            [['target_id'], 'required'],

            [['time'], 'datetime'],
        ];
    }
}
