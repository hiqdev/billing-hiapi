<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

return [
    hiqdev\yii\DataMapper\components\EntityManagerInterface::class => [
        'repositories' => [
            \hiqdev\php\billing\type\Type::class                => \hiqdev\billing\hiapi\type\TypeRepository::class,
            \hiqdev\php\billing\bill\Bill::class                => \hiqdev\billing\hiapi\bill\BillRepository::class,
            \hiqdev\php\billing\customer\Customer::class        => \hiqdev\billing\hiapi\customer\CustomerRepository::class,
            \hiqdev\php\billing\target\Target::class            => \hiqdev\billing\hiapi\target\TargetRepository::class,
            \hiqdev\php\billing\plan\PlanInterface::class       => \hiqdev\billing\hiapi\plan\PlanRepository::class,
            \hiqdev\php\billing\plan\Plan::class                => \hiqdev\billing\hiapi\plan\PlanRepository::class,
            \hiqdev\php\billing\price\PriceInterface::class     => \hiqdev\billing\hiapi\price\PriceRepository::class,
            \hiqdev\php\billing\charge\Charge::class            => \hiqdev\billing\hiapi\charge\ChargeRepository::class,
            \hiqdev\php\billing\charge\ChargeInterface::class   => \hiqdev\billing\hiapi\charge\ChargeRepository::class,
            \hiqdev\php\billing\action\Action::class            => \hiqdev\billing\hiapi\action\ActionRepository::class,
            \hiqdev\php\billing\sale\Sale::class                => \hiqdev\billing\hiapi\sale\SaleRepository::class,
            \DateTimeImmutable::class                           => \hiqdev\billing\hiapi\vo\DateTimeImmutableRepository::class,
        ],
    ],
    \hiqdev\yii\DataMapper\hydrator\ConfigurableAggregateHydrator::class => [
        'hydrators' => [
            \hiqdev\php\billing\customer\Customer::class        => \hiqdev\billing\hiapi\customer\CustomerHydrator::class,
            \hiqdev\php\billing\formula\FormulaInterface::class => \hiqdev\billing\hiapi\formula\FormulaHydrator::class,
            \hiqdev\php\billing\action\Action::class            => \hiqdev\billing\hiapi\action\ActionHydrator::class,
            \hiqdev\php\billing\action\ActionState::class       => \hiqdev\billing\hiapi\action\ActionStateHydrator::class,
            \hiqdev\php\billing\charge\Charge::class            => \hiqdev\billing\hiapi\charge\ChargeHydrator::class,
            \hiqdev\php\billing\charge\ChargeInterface::class   => \hiqdev\billing\hiapi\charge\ChargeHydrator::class,
            \hiqdev\php\billing\charge\ChargeState::class       => \hiqdev\billing\hiapi\charge\ChargeStateHydrator::class,
            \hiqdev\php\billing\bill\Bill::class                => \hiqdev\billing\hiapi\bill\BillHydrator::class,
            \hiqdev\php\billing\bill\BillState::class           => \hiqdev\billing\hiapi\bill\BillStateHydrator::class,
            \hiqdev\php\billing\target\Target::class            => \hiqdev\billing\hiapi\target\TargetHydrator::class,
            \hiqdev\php\billing\target\TargetCollection::class  => \hiqdev\billing\hiapi\target\TargetHydrator::class,
            \hiqdev\php\billing\type\Type::class                => \hiqdev\billing\hiapi\type\TypeHydrator::class,
            \hiqdev\php\billing\plan\Plan::class                => \hiqdev\billing\hiapi\plan\PlanHydrator::class,
            \hiqdev\php\billing\plan\PlanInterface::class       => \hiqdev\billing\hiapi\plan\PlanHydrator::class,
            \hiqdev\php\billing\price\PriceInterface::class     => \hiqdev\billing\hiapi\price\PriceHydrator::class,
            \hiqdev\php\billing\price\EnumPrice::class          => \hiqdev\billing\hiapi\price\EnumPriceHydrator::class,
            \hiqdev\php\billing\price\SinglePrice::class        => \hiqdev\billing\hiapi\price\SinglePriceHydrator::class,
            \hiqdev\php\billing\sale\Sale::class                => \hiqdev\billing\hiapi\sale\SaleHydrator::class,
            \hiqdev\php\units\Quantity::class                   => \hiqdev\billing\hiapi\vo\QuantityHydrator::class,
            \Money\Money::class                                 => \hiqdev\billing\hiapi\vo\MoneyHydrator::class,
        ],
    ],
    \hiqdev\php\billing\formula\FormulaEngineInterface::class => [
        '__class' => \hiqdev\php\billing\formula\FormulaEngine::class,
    ],
    \hiqdev\php\billing\bill\BillRepositoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\bill\BillRepository::class,
    ],
    \hiqdev\php\billing\plan\PlanRepositoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\plan\PlanRepository::class,
    ],
    \hiqdev\php\billing\sale\SaleRepositoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\sale\SaleRepository::class,
    ],
    \hiqdev\php\billing\order\CalculatorInterface::class => [
        '__class' => \hiqdev\php\billing\order\Calculator::class,
    ],
    \hiqdev\php\billing\tools\AggregatorInterface::class => [
        '__class' => \hiqdev\php\billing\tools\DbMergingAggregator::class,
    ],
    \hiqdev\php\billing\tools\MergerInterface::class => [
        '__class' => \hiqdev\billing\hiapi\tools\Merger::class,
    ],
    \hiqdev\php\billing\charge\GeneralizerInterface::class => [
        '__class' => \hiqdev\billing\hiapi\charge\Generalizer::class,
    ],
    \hiqdev\php\billing\type\TypeFactoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\type\TypeFactory::class,
    ],
    \hiqdev\php\billing\target\TargetFactoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\target\TargetFactory::class,
    ],
    \hiqdev\php\billing\bill\BillFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\bill\BillFactory::class,
    ],
    \hiqdev\php\billing\customer\CustomerFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\customer\CustomerFactory::class,
    ],
    \hiqdev\php\billing\plan\PlanFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\plan\PlanFactory::class,
    ],
    \hiqdev\php\billing\price\PriceFactoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\price\PriceFactory::class,
        '__construct()' => [
            'types'         => $params['billing-hiapi.price.types'],
            'defaultClass'  => $params['billing-hiapi.price.defaultClass'],
        ],
    ],
    \hiqdev\php\billing\sale\SaleFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\sale\SaleFactory::class,
    ],
    \hiqdev\billing\hiapi\type\TypeSemantics::class,
];
