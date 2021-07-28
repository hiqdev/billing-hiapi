<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use DateTimeImmutable;
use hiqdev\php\billing\bill\Bill;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\bill\BillRequisite;
use hiqdev\php\billing\bill\BillState;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Money\Money;

/**
 * Bill Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class BillHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function hydrate(array $row, $object)
    {
        $row['type']        = $this->hydrator->create($row['type'],     Type::class);
        $row['time']        = $this->hydrator->create($row['time'],     DateTimeImmutable::class);
        $row['sum']         = $this->hydrator->create($row['sum'],      Money::class);
        $row['quantity']    = $this->hydrator->create($row['quantity'], Quantity::class);
        $row['customer']    = $this->hydrator->create($row['customer'], Customer::class);
        if (isset($row['target'])) {
            $row['target']  = $this->hydrator->create($row['target'],   Target::class);
        }
        if (isset($row['plan'])) {
            $row['plan']    = $this->hydrator->create($row['plan'],     Plan::class);
        }
        if (isset($row['state'])) {
            $row['state']  = $this->hydrator->create($row['state'],   BillState::class);
        }
        if (isset($row['requisite'])) {
            $row['requisite'] = $this->hydrator->create($row['requisite'], BillRequisite::class);
        }

        $raw_charges = $row['charges'];
        unset($row['charges']);

        /** @var Bill $bill */
        $bill = parent::hydrate($row, $object);

        if (\is_array($raw_charges)) {
            $charges = [];
            foreach ($raw_charges as $key => $charge) {
                if ($charge instanceof ChargeInterface) {
                    $charge->setBill($bill);
                    $charges[$key] = $charge;
                } else {
                    $charge['bill'] = $bill;
                    $charges[$key] = $this->hydrator->hydrate($charge, ChargeInterface::class);
                }
            }
            $bill->setCharges($charges);
        }

        return $bill;
    }

    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function extract($object)
    {
        return array_filter([
            'id'            => $object->getId(),
            'type'          => $this->hydrator->extract($object->getType()),
            'time'          => $this->hydrator->extract($object->getTime()),
            'sum'           => $this->hydrator->extract($object->getSum()),
            'quantity'      => $this->hydrator->extract($object->getQuantity()),
            'customer'      => $this->hydrator->extract($object->getCustomer()),
            'requisite'     => $object->getRequisite() ? $this->hydrator->extract($object->getRequisite()) : null,
            'target'        => $object->getTarget() ? $this->hydrator->extract($object->getTarget()) : null,
            'plan'          => $object->getPlan() ? $this->hydrator->extract($object->getPlan()) : null,
            'charges'       => $this->hydrator->extractAll($object->getCharges()),
            'state'         => $object->getState() ? $this->hydrator->extract($object->getState()) : null,
            'from'          => $object->getFrom() ? $this->hydrator->extract($object->getFrom()) : null,
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function createEmptyInstance(string $className, array $data = [])
    {
        if ($className === BillInterface::class) {
            $className = Bill::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
