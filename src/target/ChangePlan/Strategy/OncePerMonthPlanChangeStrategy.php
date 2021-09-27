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
            $saleCloseMonth = $closeTime->modify('first day of this month midnight');

            if ($saleCloseMonth->format('Y-m-d') === $desiredCloseTime->format('Y-m-d')) {
                // If sale is closed at the first day of month, then the whole month is available
                $nextPeriodStart = $saleCloseMonth;
            } else {
                // Otherwise - next month
                $nextPeriodStart = $saleCloseMonth->modify('next month');
            }
        } else {
            $nextPeriodStart = $activeSale->getTime()->modify('first day of next month midnight');
        }

        if ($desiredCloseTime < $nextPeriodStart) {
            throw new ConstraintException(sprintf(
                'Plan can not be changed earlier than %s',
                $nextPeriodStart->format(DATE_ATOM)
            ));
        }

        $desiredCloseMonth = $desiredCloseTime->modify('first day of this month midnight');
        if ($desiredCloseTime > $desiredCloseMonth) {
            throw new ConstraintException(sprintf(
                'Plan change in the middle of month is prohibited, as there will be multiple active sales in the same month. ' .
                'Either change plan at %s in this month, or change it next month at %s.',
                $desiredCloseMonth->format(DATE_ATOM),
                $desiredCloseMonth->modify('next month')->format(DATE_ATOM)
            ));
        }
    }

    public function calculateTimeInPreviousSalePeriod(SaleInterface $activeSale, DateTimeImmutable $desiredCloseTime): DateTimeImmutable
    {
        return $desiredCloseTime->modify('first day of previous month midnight');
    }
}
