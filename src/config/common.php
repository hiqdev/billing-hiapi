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
                \hiqdev\php\billing\type\Type::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\TypeRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\query\TypeQuery::class,
                ],
                \hiqdev\php\billing\bill\Bill::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\BillRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\query\BillQuery::class,
                ],
                \hiqdev\php\billing\customer\Customer::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\CustomerRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\query\CustomerQuery::class,
                ],
                \hiqdev\php\billing\Plan::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\ar\ArPlanRepository::class,
                    'recordClass' => \hiqdev\billing\hiapi\models\Plan::class,
                ],
                \hiqdev\php\billing\target\Target::class => [
                    'class' => \hiqdev\billing\hiapi\repositories\TargetRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\query\TargetQuery::class,
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            \hiqdev\php\billing\type\TypeFactoryInterface::class => [
                'class' => \hiqdev\php\billing\type\TypeFactory::class,
            ],
            \hiqdev\php\billing\target\TargetFactoryInterface::class => [
                'class' => \hiqdev\php\billing\target\TargetFactory::class,
            ],
            \hiqdev\php\billing\bill\BillFactoryInterface::class => [
                'class' => \hiqdev\php\billing\bill\BillFactory::class,
            ],
            \hiqdev\php\billing\customer\CustomerFactoryInterface::class => [
                'class' => \hiqdev\php\billing\customer\CustomerFactory::class,
            ],
        ],
    ],
];
