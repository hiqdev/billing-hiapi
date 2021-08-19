<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan\Strategy;

use DateTimeImmutable;
use hiqdev\php\billing\Exception\ConstraintException;
use hiqdev\php\billing\sale\SaleInterface;

interface PlanChangeStrategyInterface
{
    public function calculateTimeInPreviousSalePeriod(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): DateTimeImmutable;

    /**
     * Checks, whether the passed $activeSale can be closed at the passed $desiredCloseTime.
     * This method should be used only in the plan change workflow.
     *
     * @param SaleInterface $activeSale
     * @param DateTimeImmutable $desiredCloseTime
     * @throws ConstraintException when the sale can not be closed at the passed time
     */
    public function ensureSaleCanBeClosedForChangeAtTime(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): void;
}
