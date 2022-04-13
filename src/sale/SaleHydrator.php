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

use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;

/**
 * Class SaleHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class SaleHydrator extends GeneratedHydrator
{
    public function __construct()
    {
        $this->addStrategy('time', new DateTimeImmutableFormatterStrategy(new DateTimeFormatterStrategy()));
        $this->addStrategy('closeTime', new NullableStrategy(
            new DateTimeImmutableFormatterStrategy(new DateTimeFormatterStrategy())
        ));
    }

    public function hydrate(array $data, $object): object
    {
        $data['target'] = $this->hydrateChild($data['target'] ?? null, TargetInterface::class);
        $data['customer'] = $this->hydrateChild($data['customer'], CustomerInterface::class);

        $data['plan'] = $data['plan'] ?? null;
        if (is_array($data['plan']) && !empty($data['plan'])) {
            $data['plan'] = $this->hydrateChild($data['plan'], PlanInterface::class);
        }

        $data['time'] = $this->hydrateValue('time', $data['time']);
        $data['closeTime'] = $this->hydrateValue('closeTime', $data['closeTime'] ?? null);

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Sale $object
     */
    public function extract($object): array
    {
        return array_filter([
            'id' => $object->getId(),
            'target' => $this->extractChild($object->getTarget()),
            'customer' => $this->extractChild($object->getCustomer()),
            'plan' => $this->extractChild($object->getPlan()),
            'time' => $this->extractValue('time', $object->getTime()),
            'closeTime' => $this->extractValue('closeTime', $object->getCloseTime()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        if ($className === SaleInterface::class) {
            $className = Sale::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
