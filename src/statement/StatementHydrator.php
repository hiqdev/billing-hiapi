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

use DateTimeImmutable;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\statement\Statement;
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

        $raw_bills = $row['bills'];
        unset($row['bills']);

        /** @var Statement $statement */
        $statement = parent::hydrate($row, $object);

        if (\is_array($raw_bills)) {
            $bills = [];
            foreach ($raw_bills as $key => $bill) {
                if (! $bill instanceof BillInterface) {
                    $bill = $this->hydrator->hydrate($bill, BillInterface::class);
                }
                $bills[$key] = $bill;
            }
            $statement->setBills($bills);
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
            'bills'       => $this->hydrator->extractAll($object->getBills()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
