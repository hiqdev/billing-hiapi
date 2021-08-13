<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan\Strategy;

use DateTimeImmutable;
use hiqdev\php\billing\Exception\ConstraintException;
use hiqdev\php\billing\sale\SaleInterface;

class GeneralPlanChangeStrategy implements PlanChangeStrategyInterface
{
    public function ensureSaleCanBeClosedForChangeAtTime(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): void
    {
        if ($activeSale->getTime() > $desiredCloseTime) {
            throw new ConstraintException(sprintf(
                'Plan can not be changed earlier than the active sale "%s" was created',
                $activeSale->getId()
            ));
        }

        if ($activeSale->getCloseTime() !== null && $activeSale->getCloseTime() > $desiredCloseTime) {
            throw new ConstraintException(sprintf(
                'Currently active sale "%s" is closed at "%s". Could not change plan earlier than active sale close time.',
                $activeSale->getId(),
                $activeSale->getCloseTime()->format(DATE_ATOM)
            ));
        }
    }

    public function calculateTimeInPreviousSalePeriod(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): DateTimeImmutable
    {
        return $activeSale->getTime()->modify('-1 second');
    }
}
