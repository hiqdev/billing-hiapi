<?php

namespace hiqdev\billing\hiapi\controllers;

class PlanController extends \hiapi\controllers\BaseController
{
    public function commands()
    {
        return array_merge(parent::commands(), [
            'search' => [
                'class'  => \hiapi\commands\SearchCommand::class,
                'handler' => \hiqdev\billing\hiapi\commands\plan\SearchHandler::class,
            ],
        ]);
    }
}

