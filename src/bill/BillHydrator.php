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
    protected array $existingAttributes = [
        'type' => Type::class,
        'time' => DateTimeImmutable::class,
        'quantity' => Quantity::class,
        'sum' => Money::class,
        'customer' => Customer::class,
    ];

    protected array $issetAttributes = [
        'target' => Target::class,
        'plan' => Plan::class,
        'state' => BillState::class,
        'requisite' => BillRequisite::class,
    ];
    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function hydrate(array $row, $object)
    {
        foreach ($this->existingAttributes as $attr => $class) {
            $row[$attr] = $this->hydrator->create($row[$attr], $class);
        }

        foreach ($this->issetAttributes as $attr => $class) {
            if (isset($row[$attr])) {
                $row[$attr] = !empty(array_filter($row[$attr])) ? $this->hydrator->create($row[$attr], $class) : null;
            }
        }

        $raw_charges = $row['charges'] ?? null;
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
