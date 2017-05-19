<?php

namespace hiqdev\billing\hiapi\controllers;

class OrderController extends \hiapi\controllers\BaseController
{
    public function commands()
    {
        return array_merge(parent::commands(), [
            'ping' => [
                'class'  => \hiapi\commands\PingCommand::class,
                'answer' => 'order BANG',
            ],
        ]);
    }
}
