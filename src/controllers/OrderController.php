<?php

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
