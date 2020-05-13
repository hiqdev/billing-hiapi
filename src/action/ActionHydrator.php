<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action;

use DateTimeImmutable;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\action\ActionState;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
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
        $data['type']       = $this->hydrator->create($data['type'], Type::class);
        $data['target']     = $this->hydrator->create($data['target'], Target::class);
        $data['quantity']   = $this->hydrator->create($data['quantity'], Quantity::class);
        $data['customer']   = $this->hydrator->create($data['customer'], Customer::class);
        $data['time']       = $this->hydrator->create($data['time'], DateTimeImmutable::class);
        if (isset($data['sale'])) {
            $data['sale']   = $this->hydrator->create($data['sale'], Sale::class);
        }
        if (isset($data['parent'])) {
            $data['parent'] = $this->hydrator->create($data['parent'], Action::class);
        }
        if (isset($data['state'])) {
            $data['state']  = $this->hydrator->create($data['state'], ActionState::class);
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
            'state'         => $object->getState() ? $this->hydrator->extract($object->getState()) : null,
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);

        return $result;
    }
}
