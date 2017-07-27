<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

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
