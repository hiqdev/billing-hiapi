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

use hiqdev\billing\hiapi\Hydrator\Helper\DateTimeImmutableFormatterStrategyHelper;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\action\ActionState;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

/**
 * Action Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ActionHydrator extends GeneratedHydrator
{
    public function __construct()
    {
        $this->addStrategy('time', DateTimeImmutableFormatterStrategyHelper::create());
    }

    /** {@inheritdoc} */
    public function hydrate(array $data, $object): object
    {
        $data['type']       = $this->hydrator->create($data['type'] ?? null, Type::class);
        $data['target']     = $this->hydrator->create($data['target'] ?? null, Target::class);
        $data['quantity']   = $this->hydrator->create($data['quantity'], Quantity::class);
        $data['customer']   = $this->hydrator->create($data['customer'], Customer::class);
        $data['time']       = $this->hydrateValue('time', $data['time'] ?? 'now');
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
     * @param Action $object
     */
    public function extract($object): array
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'type'          => $this->hydrator->extract($object->getType()),
            'target'        => $this->hydrator->extract($object->getTarget()),
            'quantity'      => $this->hydrator->extract($object->getQuantity()),
            'customer'      => $this->hydrator->extract($object->getCustomer()),
            'time'          => $this->extractValue('time', $object->getTime()),
            'sale'          => $object->getSale() ? $this->hydrator->extract($object->getSale()) : null,
            'parent'        => $object->getParent() ? $this->hydrator->extract($object->getParent()) : null,
            'state'         => $object->getState() ? $this->hydrator->extract($object->getState()) : null,
            'usage_interval'=> $this->hydrator->extract($object->getUsageInterval()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);

        return $result;
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        return parent::createEmptyInstance(Action::class, $data);
    }
}
