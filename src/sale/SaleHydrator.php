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
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\target\TargetInterface;
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
        $data['target']     = $this->hydrateChild($data['target'] ?? null, TargetInterface::class);
        $data['customer']   = $this->hydrateChild($data['customer'], CustomerInterface::class);
        $data['plan']       = $this->hydrateChild($data['plan'], PlanInterface::class);
        $data['time']       = $this->hydrateChild($data['time'], DateTimeImmutable::class);
        if (isset($data['closeTime'])) {
            $data['closeTime']  = $this->hydrateChild($data['closeTime'], DateTimeImmutable::class);
        }

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
            'target'    => $this->extractChild($object->getTarget()),
            'customer'  => $this->extractChild($object->getCustomer()),
            'plan'      => $this->extractChild($object->getPlan()),
            'time'      => $this->extractChild($object->getTime()),
            'closeTime' => $this->extractChild($object->getCloseTime()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function createEmptyInstance(string $className, array $data = [])
    {
        if ($className === SaleInterface::class) {
            $className = Sale::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
