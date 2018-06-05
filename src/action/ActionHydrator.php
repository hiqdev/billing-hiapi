<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action;

use DateTimeImmutable;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\units\Quantity;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

/**
 * Action Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ActionHydrator extends GeneratedHydrator
{
    /** {@inheritdoc} */
    public function hydrate(array $data, $object)
    {
        $data['type'] = $this->hydrator->hydrate($data['type'], Type::class);
        $data['target'] = $this->hydrator->hydrate($data['target'], Target::class);
        $data['quantity'] = $this->hydrator->hydrate($data['quantity'], Quantity::class);
        $data['customer'] = $this->hydrator->hydrate($data['customer'], Customer::class);
        $data['time'] = $this->hydrator->hydrate([$data['time']], DateTimeImmutable::class);
        if (isset($data['sale'])) {
            $data['sale'] = $this->hydrator->hydrate($data['sale'], Sale::class);
        }
        if (isset($data['parent'])) {
            $data['parent'] = $this->hydrator->hydrate($data['parent'], Action::class);
        }

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Action $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'type'          => $this->hydrator->extract($object->getType()),
            'target'        => $this->hydrator->extract($object->getTarget()),
            'quantity'      => $this->hydrator->extract($object->getQuantity()),
            'customer'      => $this->hydrator->extract($object->getCustomer()),
            'time'          => $this->hydrator->extract($object->getTime()),
            'sale'          => $object->getSale() ? $this->hydrator->extract($object->getSale()) : null,
            'parent'        => $object->getParent() ? $this->hydrator->extract($object->getParent()) : null,
        ]);

        return $result;
    }
}
