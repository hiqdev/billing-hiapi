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

namespace hiqdev\billing\hiapi\feature;

use hiqdev\billing\hiapi\Hydrator\Helper\DateTimeImmutableFormatterStrategyHelper;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use Laminas\Hydrator\Strategy\NullableStrategy;

/**
 * Class FeatureHydrator.
 */
class FeatureHydrator extends GeneratedHydrator
{
    public function __construct()
    {
        $this->addStrategy('starts', DateTimeImmutableFormatterStrategyHelper::create());
        $this->addStrategy('expires', new NullableStrategy(DateTimeImmutableFormatterStrategyHelper::create()));
    }

    /**
     * {@inheritdoc}
     * @param object|Feature $object
     */
    public function hydrate(array $data, $object): object
    {
        $data['target'] = $this->hydrator->create($data['target'], Target::class);
        $data['type'] = $this->hydrator->create($data['type'], Type::class);
        $data['starts'] = $this->hydrateValue('starts', $data['starts']);
        if (!empty($data['expires'])) {
            $data['expires'] = $this->hydrateValue('expires', $data['expires']);
        }

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Feature $object
     */
    public function extract($object): array
    {
        return [
            'id'   => $object->getId(),
            'type' => $this->hydrator->extract($object->type()),
            'starts' => $this->extractValue('starts', $object->starts()),
            'expires' => $this->extractValue('expires', $object->expires()),
            'target' => $this->hydrator->extract($object->target()),
        ];
    }
}
