<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use DateTimeImmutable;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\Target;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

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
        $data['plan']       = $this->hydrator->hydrate($data['plan'], Plan::class);
        $data['time']       = new DateTimeImmutable($data['time']);

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Sale $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'time'          => $object->time ? $object->time->format(DateTimeImmutable::ATOM) : null,
        ]);

        if ($object->getPlan()) {
            $result['plan'] = $this->hydrator->extract($object->getPlan());
        }
        if ($object->getTarget()) {
            $result['target'] = $this->hydrator->extract($object->getTarget());
        }
        if ($object->getCustomer()) {
            $result['customer'] = $this->hydrator->extract($object->getCustomer());
        }

        return $result;
    }
}
