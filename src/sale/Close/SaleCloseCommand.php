<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Close;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiqdev\DataMapper\Validator\DateTimeValidator;

class SaleCloseCommand extends BaseCommand
{
    public $customer_id;

    public $plan_id;

    public $target_id;
    public $target_fullname;

    public $time;

    public $customer;

    public $plan;

    public $target;

    public function rules(): array
    {
        return [
            [['customer_id'], IdValidator::class],

            [['plan_id'], IdValidator::class],

            [['target_id'], IdValidator::class],
            [['target_fullname'], 'string'],

            [['time'], DateTimeValidator::class],
            [['time'], 'required']
        ];
    }
}
