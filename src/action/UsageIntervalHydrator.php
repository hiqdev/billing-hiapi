<?php
declare(strict_types=1);
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
use hiqdev\php\billing\action\UsageInterval;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Laminas\Hydrator\Strategy\NullableStrategy;

class UsageIntervalHydrator extends GeneratedHydrator
{
    public function __construct()
    {
        $this->addStrategy('start', DateTimeImmutableFormatterStrategyHelper::create());
        $this->addStrategy('end', new NullableStrategy(DateTimeImmutableFormatterStrategyHelper::create()));
        $this->addStrategy('month', DateTimeImmutableFormatterStrategyHelper::create());
    }

    /** {@inheritdoc} */
    public function hydrate(array $data, $object): object
    {
        $data['start'] = $this->hydrateValue('start', $data['start']);
        $data['end']   = $this->hydrateValue('end', $data['end']);
        $data['month'] = $this->hydrateValue('month', $data['month']);

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param UsageInterval $object
     */
    public function extract($object): array
    {
        $result = array_filter([
            'start'            => $this->extractValue('start', $object->start()),
            'end'              => $this->extractValue('end', $object->end()),
            'seconds'          => $object->seconds(),
            'ratio'            => $object->ratioOfMonth(),
            'seconds_in_month' => $object->secondsInMonth(),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);

        return $result;
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        return parent::createEmptyInstance(UsageInterval::class, $data);
    }
}
