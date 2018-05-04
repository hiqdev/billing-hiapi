<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\controllers;

use hiqdev\billing\hiapi\commands\order\CalculateValueCommand;

class OrderController extends \hiapi\controllers\BaseController
{
    public function commands()
    {
        return array_merge(parent::commands(), [
            'ping' => [
                'class'  => \hiapi\commands\PingCommand::class,
                'answer' => 'order BANG',
            ],
            'calculate' => [
                'class'  => CalculateValueCommand::class,
            ],
        ]);
    }
}
