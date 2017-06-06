<?php

namespace hiqdev\billing\hiapi\controllers;

use hiqdev\php\billing\bill\Bill;

class BillController extends \hiapi\controllers\BaseController
{
    protected $entityClass = Bill::class;

    public function commands()
    {
        return array_merge(parent::commands(), [
            'search' => [
                'class'  => \hiapi\commands\SearchCommand::class,
            ],
        ]);
    }
}
