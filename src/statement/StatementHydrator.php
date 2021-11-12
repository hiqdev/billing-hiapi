<?php
declare(strict_types=1);

/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\statement;

use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\statement\StatementBillInterface;
use hiqdev\php\billing\statement\Statement;
use hiqdev\php\billing\plan\PlanInterface;
use DateTimeImmutable;
use Money\Money;

/**
 * Statement Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class StatementHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Statement $object
     */
    public function hydrate(array $row, $object)
    {
        $row['time']        = $this->hydrator->create($row['time'],     DateTimeImmutable::class);
        $row['balance']     = $this->hydrator->create($row['balance'],  Money::class);
        $row['customer']    = $this->hydrator->create($row['customer'], CustomerInterface::class);
        $row['month']       = $this->hydrator->create($row['month'],    DateTimeImmutable::class);
        $row['total']       = $this->hydrator->create($row['total'],    Money::class);
        $row['payment']     = $this->hydrator->create($row['payment'],  Money::class);
        $row['amount']      = $this->hydrator->create($row['amount'],   Money::class);

        $raw_bills = $row['bills'];
        $raw_plans = $row['plans'];
        unset($row['bills'], $row['plans']);

        /** @var Statement $statement */
        $statement = parent::hydrate($row, $object);

        if (\is_array($raw_bills)) {
            $bills = [];
            foreach ($raw_bills as $key => $bill) {
                if (! $bill instanceof StatementBillInterface) {
                    $bill = $this->hydrator->hydrate($bill, StatementBillInterface::class);
                }
                $bills[$key] = $bill;
            }
            $statement->setBills($bills);
        }

        if (\is_array($raw_plans)) {
            $plans = [];
            foreach ($raw_plans as $key => $plan) {
                if (! $plan instanceof PlanInterface) {
                    $plan = $this->hydrator->hydrate($bill, PlanInterface::class);
                }
                $plans[$key] = $plan;
            }
            $statement->setPlans($plans);
        }

        return $statement;
    }

    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function extract($object)
    {
        return array_filter([
            'period'        => $object->getPeriod(),
            'time'          => $this->hydrator->extract($object->getTime()),
            'balance'       => $this->hydrator->extract($object->getBalace()),
            'month'         => $this->hydrator->extract($object->getMonth()),
            'total'         => $this->hydrator->extract($object->getTotal()),
            'payment'       => $this->hydrator->extract($object->getPayment()),
            'amount'        => $this->hydrator->extract($object->getAmount()),
            'bills'         => $this->hydrator->extractAll($object->getBills()),
            'plans'         => $this->hydrator->extractAll($object->getPlans()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
