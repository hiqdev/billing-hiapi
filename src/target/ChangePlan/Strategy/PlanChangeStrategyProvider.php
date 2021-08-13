<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan\Strategy;

use hiqdev\php\billing\sale\SaleInterface;

class PlanChangeStrategyProvider implements PlanChangeStrategyProviderInterface
{
    private GeneralPlanChangeStrategy $generalPlanChangeStrategy;
    private OncePerMonthPlanChangeStrategy $oncePerMonthPlanChangeStrategy;

    public function __construct(
        GeneralPlanChangeStrategy $generalPlanChangeStrategy,
        OncePerMonthPlanChangeStrategy $oncePerMonthPlanChangeStrategy
    ) {
        $this->generalPlanChangeStrategy = $generalPlanChangeStrategy;
        $this->oncePerMonthPlanChangeStrategy = $oncePerMonthPlanChangeStrategy;
    }

    public function getBySale(SaleInterface $sale): PlanChangeStrategyInterface
    {
        if (in_array($sale->getTarget()->getType(), [
            // TODO: Add a property to a type, that will distinguish strategies
            'anycastcdn', 'storage', 'videocdn'
        ], true)) {
            return $this->oncePerMonthPlanChangeStrategy;
        }

        return $this->generalPlanChangeStrategy;
    }
}
