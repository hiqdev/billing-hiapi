<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2021, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\statement;

use hiqdev\php\billing\statement\StatementBill;
use hiqdev\php\billing\statement\StatementBillInterface;
use hiqdev\billing\hiapi\bill\BillHydrator;
use hiqdev\php\billing\bill\BillRequisite;
use hiqdev\php\billing\bill\BillState;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use DateTimeImmutable;
use Money\Money;

/**
 * Statement Bill Hydrator.
 *
 * @author Yurii Myronchuk <bladeroot@gmail.com>
 */
class StatementBillHydrator extends BillHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function hydrate(array $row, $object)
    {
        $row['type']        = $this->hydrator->create($row['type'],     Type::class);
        $row['month']       = $this->hydrator->create($row['month'],    DateTimeImmutable::class);
        $row['time']        = $this->hydrator->create($row['time'],     DateTimeImmutable::class);
        $row['sum']         = $this->hydrator->create($row['sum'],      Money::class);
        $row['quantity']    = $this->hydrator->create($row['quantity'], Quantity::class);
        $row['customer']    = $this->hydrator->create($row['customer'], Customer::class);
        $row['price']       = $this->hydrator->create($row['price'],    Money::class);
        $row['overuse']     = $this->hydrator->create($row['overuse'],  Money::class);
        $row['prepaid']     = $this->hydrator->create($row['prepaid'],  Quantity::class);

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
     * @param object|StatementBill $object
     */
    public function extract($object)
    {
        return array_filter(array_merge(parent::extract($object), [
            'month'         => $this->hydrator->extract($object->getMonth()),
            'from'          => $object->getFrom() ? $this->hydrator->extract($object->getFrom()) : null,
            'price'         => $object->getPrice() ? $this->hydrator->extract($object->getPrice()) : null,
            'overuse'       => $object->getOveruse() ? $this->hydrator->extract($object->getOveruse()) : null,
            'prepaid'       => $object->getPrepaid() ? $this->hydrator->extract($object->getPrepaid()) : null,
        ]), static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function createEmptyInstance(string $className, array $data = [])
    {
        if ($className === StatementBillInterface::class) {
            $className = StatementBill::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
