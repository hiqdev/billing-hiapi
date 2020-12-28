<?php
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
use hiqdev\php\billing\statement\Statement;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
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

        $raw_charges = $row['charges'];
        unset($row['charges']);

        /** @var Statement $statement */
        $statement = parent::hydrate($row, $object);

        if (\is_array($raw_charges)) {
            $charges = [];
            foreach ($raw_charges as $key => $charge) {
                if ($charge instanceof ChargeInterface) {
                    $charges[$key] = $charge;
                } else {
                    $charges[$key] = $this->hydrator->hydrate($charge, ChargeInterface::class);
                }
            }
            $statement->setCharges($charges);
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
            'charges'       => $this->hydrator->extractAll($object->getCharges()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
