<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return [
    'components' => [
        'entityManager' => [
            'repositories' => [
                \hiqdev\php\billing\Plan::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\ar\ArPlanRepository::class,
                    'recordClass' => \hiqdev\billing\hiapi\models\Plan::class,
                ],
                \hiqdev\php\billing\Customer::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\CustomerRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\query\CustomerQuery::class,
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            \hiqdev\php\billing\CustomerFactoryInterface::class => [
                'class' => \hiqdev\php\billing\CustomerFactory::class,
            ],
        ],
    ],
];
