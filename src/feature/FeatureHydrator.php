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

use DateTimeImmutable;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;

/**
 * Class FeatureHydrator.
 */
class FeatureHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Feature $object
     */
    public function hydrate(array $data, $object): object
    {
        $data['target'] = $this->hydrator->create($data['target'], Target::class);
        $data['type'] = $this->hydrator->create($data['type'], Type::class);
        $data['starts'] = $this->hydrator->create($data['starts'], DateTimeImmutable::class);
        if (!empty($data['expires'])) {
            $data['expires'] = $this->hydrator->create($data['expires'], DateTimeImmutable::class);
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
            'starts' => $object->starts() ? $object->starts()->format(DATE_ATOM) : null,
            'expires' => $object->expires() ? $object->expires()->format(DATE_ATOM) : null,
            'target' => $this->hydrator->extract($object->target()),
        ];
    }
}
