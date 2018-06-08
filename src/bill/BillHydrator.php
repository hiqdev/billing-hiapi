<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use DateTimeImmutable;
use hiqdev\billing\hiapi\models\Plan;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\formula\FormulaInterface;
use hiqdev\php\billing\price\PriceFactoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;
use Money\Currency;
use Money\Money;
use yii\helpers\Json;
use Zend\Hydrator\HydratorInterface;

/**
 * Bill Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class BillHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Plan $object
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

        return parent::hydrate($row, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Plan $object
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
            'target'        => $object->getTarget() ? $this->hydrator->extract($object->getTarget()) : null,
            'plan'          => $object->getPlan() ? $this->hydrator->extract($object->getPlan()) : null,
            'charges'       => $this->hydrator->extractMultiple($object->getCharges()),
        ]);
    }

}
