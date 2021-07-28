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

class Command extends GetInfoCommand
{
    public $month;

    public function getMonth()
    {
        return $this->month;
    }

    public function rules()
    {
        return [
            [['month'], 'date', 'format' => 'php:Y-m-d'],
            [['month'], 'required'],
            ['with', 'each', 'rule' => [RefValidator::class]],
        ];
    }

    public function getEntityClass(): string
    {
        return Statement::class;
    }
}
