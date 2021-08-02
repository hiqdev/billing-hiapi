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

namespace hiqdev\billing\hiapi\statement\GetInfo;

use hiapi\commands\GetInfoCommand;
use hiqdev\php\billing\statement\Statement;
use hiapi\validators\RefValidator;
use hiqdev\DataMapper\Query\Specification;

class Command extends GetInfoCommand
{
    public $month;

    public $customer_id;

    public function getMonth()
    {
        return $this->month;
    }

    public function getCustomerID()
    {
        return $this->customer_id;
    }

    public function rules()
    {
        return [
            [['month'], 'date', 'format' => 'php:Y-m-d'],
            [['month'], 'filter', 'filter' => function($value) {
                return date("Y-m-01", strtotime($value));
            }],
            [['month'], 'required'],
            [['customer_id'], 'integer', 'min' => 1],
            ['with', 'each', 'rule' => [RefValidator::class]],
        ];
    }

    public function getEntityClass(): string
    {
        return Statement::class;
    }

    public function getSpecification(): Specification
    {
        $spec = new Specification();
        $spec->where = array_filter([
            'month' => $this->month,
            'customer_id' => $this->customer_id,
        ]);
        $spec->with = $this->with;

        return $spec;
    }
}
