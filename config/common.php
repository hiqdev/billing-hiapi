<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

$singletons = [
    \hiqdev\DataMapper\Repository\EntityManagerInterface::class => [
        'repositories' => [
            \DateTimeImmutable::class => \hiqdev\billing\hiapi\vo\DateTimeImmutableRepository::class,
        ],
    ],
    \hiqdev\DataMapper\Hydrator\ConfigurableHydrator::class => [
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
    \hiqdev\php\billing\order\CalculatorInterface::class => [
        '__class' => \hiqdev\php\billing\order\Calculator::class,
    ],
    \hiqdev\php\billing\tools\AggregatorInterface::class => [
        '__class' => \hiqdev\php\billing\tools\Aggregator::class,
    ],
    \hiqdev\php\billing\tools\MergerInterface::class => [
        '__class' => \hiqdev\billing\hiapi\tools\Merger::class,
    ],
    \hiqdev\billing\hiapi\tools\Merger::class => [
        '__construct()' => [
            \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\tools\Merger::class),
        ],
    ],
    \hiqdev\php\billing\charge\GeneralizerInterface::class => [
        '__class' => \hiqdev\billing\hiapi\charge\Generalizer::class,
    ],
    \hiqdev\php\billing\action\ActionFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\action\ActionFactory::class,
    ],
    \hiqdev\php\billing\charge\ChargeFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\charge\ChargeFactory::class,
    ],
    \hiqdev\php\billing\type\TypeFactoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\type\TypeFactory::class,
    ],
    \hiqdev\php\billing\target\TargetFactoryInterface::class => [
        '__class' => \hiqdev\billing\hiapi\target\TargetFactory::class,
        '__construct()' => [
            $params['billing-hiapi.target.classmap'],
            $params['billing-hiapi.target.defaultClass'],
        ],
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
            $params['billing-hiapi.price.types'],
            $params['billing-hiapi.price.defaultClass'],
        ],
    ],
    \hiqdev\php\billing\sale\SaleFactoryInterface::class => [
        '__class' => \hiqdev\php\billing\sale\SaleFactory::class,
    ],
    \hiqdev\php\billing\tools\FactoryInterface::class => [
        '__class' => \hiqdev\php\billing\tools\Factory::class,
        '__construct()' => [
            'factories' => [
                'action'    => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\action\ActionFactoryInterface::class),
                'bill'      => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\bill\BillFactoryInterface::class),
                'charge'    => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\charge\ChargeFactoryInterface::class),
                'customer'  => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\customer\CustomerFactoryInterface::class),
                'plan'      => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\plan\PlanFactoryInterface::class),
                'price'     => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\price\PriceFactoryInterface::class),
                'sale'      => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\sale\SaleFactoryInterface::class),
                'target'    => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\target\TargetFactoryInterface::class),
                'type'      => \hiqdev\yii\compat\yii::referenceTo(\hiqdev\php\billing\type\TypeFactoryInterface::class),
            ],
            'remove keys' => new \Yiisoft\Arrays\Modifier\RemoveKeys(),
        ],
    ],

    \hiqdev\billing\hiapi\type\TypeSemantics::class,

    \hiapi\jsonApi\ResourceFactory::class => [
        '__construct()' => [
            'resourceMap' => [
                \Money\Money::class                                     => \hiqdev\billing\hiapi\vo\MoneyResource::class,
                \hiqdev\php\units\QuantityInterface::class              => \hiqdev\billing\hiapi\vo\QuantityResource::class,
                \hiqdev\php\units\UnitInterface::class                  => \hiqdev\billing\hiapi\vo\UnitResource::class,
                \hiqdev\php\billing\bill\BillInterface::class           => \hiqdev\billing\hiapi\bill\BillResource::class,
                \hiqdev\php\billing\charge\ChargeInterface::class       => \hiqdev\billing\hiapi\charge\ChargeResource::class,
                \hiqdev\php\billing\plan\PlanInterface::class           => \hiqdev\billing\hiapi\plan\PlanResource::class,
                \hiqdev\php\billing\sale\SaleInterface::class           => \hiqdev\billing\hiapi\sale\SaleResource::class,
                \hiqdev\php\billing\type\TypeInterface::class           => \hiqdev\billing\hiapi\type\TypeResource::class,
                \hiqdev\php\billing\target\TargetInterface::class       => \hiqdev\billing\hiapi\target\TargetResource::class,
                \hiqdev\php\billing\price\PriceInterface::class         => \hiqdev\billing\hiapi\price\PriceResource::class,
                \hiqdev\php\billing\customer\CustomerInterface::class   => \hiqdev\billing\hiapi\customer\CustomerResource::class,
            ],
        ],
    ],

    \hiapi\Core\Endpoint\EndpointRepository::class => [
        '__construct()' => [
            'endpoints' => [
                'PlansSearch'       => \hiqdev\billing\hiapi\plan\Search\BulkBuilder::class,
                'PlanGetInfo'       => \hiqdev\billing\hiapi\plan\GetInfo\Builder::class,

                'TargetsSearch'     => \hiqdev\billing\hiapi\target\Search\BulkBuilder::class,
                'TargetGetInfo'     => \hiqdev\billing\hiapi\target\GetInfo\Builder::class,
                'TargetPurchase'    => \hiqdev\billing\hiapi\target\Purchase\Builder::class,
                'TargetsPurchase'   => \hiqdev\billing\hiapi\target\Purchase\BulkBuilder::class,
                'TargetCreate'      => \hiqdev\billing\hiapi\target\Create\Builder::class,
                'TargetsCreate'     => \hiqdev\billing\hiapi\target\Create\BulkBuilder::class,

                'SalesSearch'       => \hiqdev\billing\hiapi\sale\Search\BulkBuilder::class,
                'SaleCreate'        => \hiqdev\billing\hiapi\sale\Create\Builder::class,
                'SaleClose'         => \hiqdev\billing\hiapi\sale\Close\SaleClose::class,

                'BillsSearch'       => \hiqdev\billing\hiapi\bill\Search\BulkBuilder::class,

                'ActionCalculate'   => \hiqdev\billing\hiapi\action\Calculate\ActionCalculate::class,
                'ActionsCalculate'  => \hiqdev\billing\hiapi\action\Calculate\ActionBulkCalculate::class,
            ],
        ],
    ],

    \Psr\Http\Message\ResponseFactoryInterface::class => \Laminas\Diactoros\ResponseFactory::class,
    \Yiisoft\Router\UrlGeneratorInterface::class => \hiapi\legacy\Http\Route\UrlGenerator::class,
];

return class_exists(\Yiisoft\Factory\Definitions\Reference::class)
    ? $singletons
    : ['container' => ['singletons' => $singletons]];
