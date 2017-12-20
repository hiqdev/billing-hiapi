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
                \hiqdev\php\billing\target\Target::class => [
                    'class' => \hiqdev\billing\hiapi\target\TargetRepository::class,
                    'queryClass' => \hiqdev\billing\hiapi\target\TargetQuery::class,
                ],
                \hiqdev\php\billing\plan\Plan::class => [
                    'class' => \hiqdev\billing\hiapi\plan\PlanRepository::class,
                ],
                \hiqdev\php\billing\price\PriceInterface::class => [
                    'class' => \hiqdev\billing\hiapi\plan\PriceRepository::class,
                ],
                \hiqdev\php\billing\charge\Charge::class => [
                    'class' => \hiqdev\billing\hiapi\charge\ChargeRepository::class,
                ],
                \hiqdev\php\billing\action\Action::class => [
                    'class' => \hiqdev\billing\hiapi\action\ActionRepository::class,
                ],
                \hiqdev\php\billing\sale\Sale::class => [
                    'class' => \hiqdev\billing\hiapi\sale\SaleRepository::class,
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            \hiqdev\php\billing\plan\PlanRepositoryInterface::class => [
                'class' => \hiqdev\billing\hiapi\plan\PlanRepository::class,
            ],
            \hiqdev\php\billing\order\CalculatorInterface::class => [
                'class' => \hiqdev\php\billing\order\Calculator::class,
            ],
            \hiqdev\php\billing\charge\AggregatorInterface::class => [
                'class' => \hiqdev\php\billing\charge\Aggregator::class,
            ],
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
            \hiqdev\php\billing\plan\PlanFactoryInterface::class => [
                'class' => \hiqdev\php\billing\plan\PlanFactory::class,
            ],
            \hiqdev\php\billing\price\PriceFactoryInterface::class => function () {
                return new \hiqdev\php\billing\price\PriceFactory(Yii::$app->params['billing-hiapi.priceTypes']);
            },
            \hiqdev\php\billing\charge\ChargeFactoryInterface::class => [
                'class' => \hiqdev\php\billing\charge\ChargeFactory::class,
            ],
            \hiqdev\php\billing\action\ActionFactoryInterface::class => [
                'class' => \hiqdev\php\billing\action\ActionFactory::class,
            ],
            \hiqdev\php\billing\sale\SaleFactoryInterface::class => [
                'class' => \hiqdev\php\billing\sale\SaleFactory::class,
            ],
        ],
    ],
];
