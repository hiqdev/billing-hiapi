<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan\Strategy;

use hiqdev\php\billing\sale\SaleInterface;

interface PlanChangeStrategyProviderInterface
{
    /**
     * @param SaleInterface $sale
     * @return PlanChangeStrategyInterface
     */
    public function getBySale(SaleInterface $sale): PlanChangeStrategyInterface;
}
