<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use DateTimeImmutable;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\target\Target;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

/**
 * Class SaleHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class SaleHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object)
    {
        $data['target']     = $this->hydrator->hydrate($data['target'], Target::class);
        $data['customer']   = $this->hydrator->hydrate($data['customer'], Customer::class);
        $data['plan']       = $data['plan'] instanceof PlanInterface
            ? $data['plan']
            : $this->hydrator->hydrate($data['plan'], Plan::class);
        $data['time']       = $this->hydrator->hydrate((array) $data['time'], DateTimeImmutable::class);

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Sale $object
     */
    public function extract($object)
    {
        return array_filter([
            'id'        => $object->getId(),
            'target'    => $this->hydrator->extract($object->getTarget()),
            'customer'  => $this->hydrator->extract($object->getCustomer()),
            'plan'      => $this->hydrator->extract($object->getPlan()),
            'time'      => $object->getTime() ? $this->hydrator->extract($object->getTime()) : null,
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
