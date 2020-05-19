<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target\Purchase;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\UsernameValidator;

class Command extends BaseCommand
{
    public $customer_username;

    public $customer_id;

    public $plan_id;

    public $name;

    public $type;

    public $remoteid;

    public $time;

    public $customer;

    public $plan;

    public function rules(): array
    {
        return [
            [['customer_username'], UsernameValidator::class],
            [['customer_id'], IdValidator::class],

            [['plan_id'], IdValidator::class],
            [['plan_id'], 'required'],

            [['name'], 'trim'],

            [['type'], 'trim'],
            [['type'], 'required'],

            [['remoteid'], 'trim'],

            [['time'], 'datetime', 'format' => 'php:Y-m-d'],
        ];
    }
}
