<?php

namespace hiqdev\billing\hiapi\controllers;

use hiqdev\php\billing\Plan;

class PlanController extends \hiapi\controllers\BaseController
{
    protected $entityClass = Plan::class;

    public function commands()
    {
        return array_merge(parent::commands(), [
            'search' => [
                'class'  => \hiapi\commands\SearchCommand::class,
            ],
        ]);
    }
}
