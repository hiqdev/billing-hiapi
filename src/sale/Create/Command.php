<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Create;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\RefValidator;
use hiapi\validators\UsernameValidator;
use hiqdev\DataMapper\Validator\DateTimeValidator;

class Command extends BaseCommand
{
    public $customer_id;
    public $customer_username;

    public $plan_id;
    public $plan_name;
    public $plan_seller;
    public $plan_fullname;

    public $target_id;
    public $target_name;
    public $target_type;
    public $target_fullname;

    public $time;

    public $customer;
    public $plan;
    public $target;

    public function rules(): array
    {
        return [
            [['customer_id'], IdValidator::class],
            [['customer_username'], UsernameValidator::class],

            [['plan_id'], IdValidator::class],
            [['plan_name'], RefValidator::class],
            [['plan_seller'], UsernameValidator::class],
            [['plan_fullname'], 'string'],

            [['target_id'], IdValidator::class],
            [['target_type'], RefValidator::class],
            [['target_name'], RefValidator::class],
            [['target_fullname'], 'string'],

            [['time'], DateTimeValidator::class],
        ];
    }
}
