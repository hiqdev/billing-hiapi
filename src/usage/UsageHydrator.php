<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\usage;

use DateTimeImmutable;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\usage\Usage;
use hiqdev\php\units\Quantity;

class UsageHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Usage $object
     */
    public function hydrate(array $data, $object): object
    {
        $data['target'] = $this->hydrator->create($data['target'], Target::class);
        $data['type'] = $this->hydrator->create($data['type'], Type::class);
        $data['time'] = $this->hydrator->create($data['time'], DateTimeImmutable::class);
        $data['amount'] = $this->hydrator->create($data['amount'], Quantity::class);

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Usage $object
     */
    public function extract($object): array
    {
        return [
            'time' => $this->hydrator->extract($object->time()),
            'type' => $this->hydrator->extract($object->type()),
            'target' => $this->hydrator->extract($object->target()),
            'amount' => $this->hydrator->extract($object->amount()),
        ];
    }
}
