<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\bill\Bill;
use hiqdev\php\billing\charge\Charge;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\php\units\Quantity;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;
use Money\Money;

/**
 * Charge Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ChargeHydrator extends GeneratedHydrator
{
    /** {@inheritdoc} */
    public function hydrate(array $data, $object)
    {
        $data['action'] = $this->hydrator->hydrate($data['action'], Action::class);
        $data['price']  = $this->hydrator->hydrate($data['price'], PriceInterface::class);
        $data['usage']  = $this->hydrator->hydrate($data['usage'], Quantity::class);
        $data['sum']    = $this->hydrator->hydrate($data['sum'], Money::class);
        if (isset($data['bill'])) {
            $data['bill'] = $this->hydrator->hydrate($data['bill'], Bill::class);
        }

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Charge $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'action'        => $this->hydrator->extract($object->getAction()),
            'price'         => $this->hydrator->extract($object->getPrice()),
            'usage'         => $this->hydrator->extract($object->getUsage()),
            'sum'           => $this->hydrator->extract($object->getSum()),
            'bill'          => $object->getBill() ? $this->hydrator->extract($object->getBill()) : null,
        ]);

        return $result;
    }
}
