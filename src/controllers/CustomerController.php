<?php

namespace hiqdev\billing\hiapi\controllers;

use hiqdev\php\billing\customer\Customer;

class CustomerController extends \hiapi\controllers\BaseController
{
    protected $entityClass = Customer::class;

    public function commands()
    {
        return array_merge(parent::commands(), [
            'search' => [
                'class'  => \hiapi\commands\SearchCommand::class,
            ],
        ]);
    }
}
