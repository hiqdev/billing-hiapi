<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\usage;

use hiqdev\billing\hiapi\Hydrator\Helper\DateTimeImmutableFormatterStrategyHelper;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\usage\Usage;
use hiqdev\php\units\Quantity;

class UsageHydrator extends GeneratedHydrator
{
    public function __construct()
    {
        $this->addStrategy('time', DateTimeImmutableFormatterStrategyHelper::create());
    }

    /**
     * {@inheritdoc}
     * @param object|Usage $object
     */
    public function hydrate(array $data, $object): object
    {
        $data['target'] = $this->hydrator->create($data['target'], Target::class);
        $data['type'] = $this->hydrator->create($data['type'], Type::class);
        $data['time'] = $this->hydrateValue('time', $data['time']);
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
            'time' => $this->extractValue('time', $object->time()),
            'type' => $this->hydrator->extract($object->type()),
            'target' => $this->hydrator->extract($object->target()),
            'amount' => $this->hydrator->extract($object->amount()),
        ];
    }
}
