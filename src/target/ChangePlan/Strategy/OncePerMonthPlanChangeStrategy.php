<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan\Strategy;

use DateTimeImmutable;
use hiqdev\php\billing\Exception\ConstraintException;
use hiqdev\php\billing\sale\SaleInterface;

final class OncePerMonthPlanChangeStrategy implements PlanChangeStrategyInterface
{
    /**
     * {@inheritdoc}
     *
     * Prevents plan change in the current month.
     */
    public function ensureSaleCanBeClosedForChangeAtTime(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): void
    {
        if (($closeTime = $activeSale->getCloseTime()) !== null) {
            $saleCloseMonth = $closeTime->modify('first day of this month 00:00');

            if ($saleCloseMonth->format('Y-m-d') === $desiredCloseTime->format('Y-m-d')) {
                // If sale is closed at the first day of month, then the whole month is available
                $nextPeriodStart = $saleCloseMonth;
            } else {
                // Otherwise - next month
                $nextPeriodStart = $saleCloseMonth->modify('next month');
            }
        } else {
            $nextPeriodStart = $activeSale->getTime()->modify('next month first day 00:00');
        }

        if ($desiredCloseTime < $nextPeriodStart) {
            throw new ConstraintException(sprintf(
                'Plan can not be changed earlier than %s',
                $nextPeriodStart->format(DATE_ATOM)
            ));
        }
    }

    public function calculateTimeInPreviousSalePeriod(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): DateTimeImmutable
    {
        return $desiredCloseTime->modify('previous month first day midnight');
    }
}
