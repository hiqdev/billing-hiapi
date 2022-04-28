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
use hiapi\validators\UuidValidator;
use hiqdev\DataMapper\Validator\DateTimeValidator;
use Throwable;
use yii\validators\InlineValidator;

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

    /** @var list<InitialUse> */
    public $initial_uses = [];

    public function rules(): array
    {
        return [
            [['customer_username'], UsernameValidator::class],
            [['customer_id'], IdValidator::class],

            [['plan_name'], 'trim'],
            [['plan_seller'], UsernameValidator::class],
            [['plan_id'], IdValidator::class],

            [['name'], 'trim'],
            [['name'], 'filter', 'filter' => 'mb_strtolower'],

            [['type'], 'trim'],
            [['type'], 'required'],

            [['remoteid'], 'trim'],
            [['remoteid'], UuidValidator::class],

            [['time'], DateTimeValidator::class],

            [['initial_uses'],
                function (string $attribute, ?array $params, InlineValidator $validator, $initial_uses) {
                    if (!empty($initial_uses) && !is_array($initial_uses)) {
                        $this->addError('initial_uses must be an array');
                        return;
                    }

                    foreach ($initial_uses as &$use) {
                        $type = $use['type'] ?? null;
                        $unit = $use['unit'] ?? null;
                        $amount = $use['amount'] ?? null;

                        if ($type === null || $unit === null || $amount === null) {
                            $this->addError($attribute, 'Initial use MUST contain `type`, `unit` and `amount` properties');
                            return;
                        }

                        try {
                            $use = InitialUse::fromScalar($type, $unit, $amount);
                        } catch (Throwable $exception) {
                            $this->addError($attribute, $exception->getMessage());
                            return;
                        }
                    }

                    $this->initial_uses = $initial_uses;
                },
            ],
        ];
    }
}
