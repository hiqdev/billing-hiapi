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
    protected array $existingAttributes = [
        'type' => Type::class,
        'month' => DateTimeImmutable::class,
        'time' => DateTimeImmutable::class,
        'sum' => Money::class,
        'quantity' => Quantity::class,
        'customer' => Customer::class,
        'price' => Money::class,
        'overuse' => Money::class,
        'prepaid' => Quantity::class,
    ];

    protected array $issetAttributes = [
        'target' => Target::class,
        'plan' => Plan::class,
        'state' => BillState::class,
        'requisite' => BillRequisite::class,
        'tariff_type' => Type::class,
    ];
    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function hydrate(array $row, $object)
    {

        /** @var StatementBill $bill */
        return parent::hydrate($row, $object);
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
            'tariff_type'   => $object->getTariffType() ? $this->hydrator->extract($object->getTariffType()) : null,
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
