<?php
declare(strict_types=1);
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target\ChangePlan;

use DateTimeImmutable;
use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\UsernameValidator;
use hiqdev\DataMapper\Validator\DateTimeValidator;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\target\TargetInterface;

class Command extends BaseCommand
{
    public $customer_username;
    public $customer_id;
    public ?Customer $customer = null;

    public $plan_id;
    public $plan_name;
    public $plan_seller;
    public ?PlanInterface $plan;

    public $name;
    public $type;
    /**
     * @var string ID of the service instance in the service provider's subsystem,
     * that uniquely identifies the object or service, being sold.
     */
    public $remoteid;
    public ?TargetInterface $target;

    /** @var DateTimeImmutable */
    public $time;

    /**
     * @var ?DateTimeImmutable time, when the tariff plan change was requested.
     * Optional, is used to process events, accumulated in the message broker.
     * Requires higher permissions to be used.
     */
    public $wall_time;

    /**
     * @var bool whether to skip the target check belonging to the customer.
     */
    private bool $checkBelonging = true;

    public function rules(): array
    {
        return [
            [['plan_name'], 'trim'],
            [['plan_seller'], UsernameValidator::class],
            [['plan_id'], IdValidator::class],

            [['customer_username'], 'trim'],
            [['customer_username'], UsernameValidator::class],
            [['customer_id'], IdValidator::class],

            [['name'], 'trim'],

            [['type'], 'trim'],
            [['type'], 'required'],

            [['remoteid'], 'trim'],

            [['time'], DateTimeValidator::class],
            [['wall_time'], DateTimeValidator::class],
        ];
    }

    public function checkBelonging(): bool
    {
        return $this->checkBelonging;
    }

    public function skipCheckBelonging(): void
    {
        $this->checkBelonging = false;
    }
}
