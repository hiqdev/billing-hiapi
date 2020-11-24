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
use hiapi\validators\RefValidator;
use hiqdev\DataMapper\Validator\DateTimeValidator;

class Command extends BaseCommand
{
    public $customer_username;
    public $customer_id;

    public $plan_id;
    public $plan_name;
    public $plan_seller;

    public $name;
    public $type;

    /**
     * @var string ID of the service instance in the service provider's subsystem,
     * that uniquely identifies the object or service, being sold.
     */
    public $remoteid;

    public $time;
    public $customer;
    public $plan;

    public function rules(): array
    {
        return [
            [['customer_username'], UsernameValidator::class],
            [['customer_id'], IdValidator::class],

            [['plan_name'], RefValidator::class],
            [['plan_seller'], UsernameValidator::class],
            [['plan_id'], IdValidator::class],

            [['name'], 'trim'],

            [['type'], 'trim'],
            [['type'], 'required'],

            [['remoteid'], 'trim'],

            [['time'], DateTimeValidator::class]
        ];
    }
}
