<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan;

use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Laminas\Hydrator\HydratorInterface;

/**
 * Class PlanReadModelHydrator hydrates {@see PlanReadModel}
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PlanReadModelHydrator extends GeneratedHydrator
{
    private PlanHydrator $planHydrator;

    public function __construct(PlanHydrator $planHydrator)
    {
        $this->planHydrator = $planHydrator;
    }

    public function hydrate(array $data, $object): object
    {
        $plan = $this->planHydrator->hydrate($data, $object);

        $additionalData = [];
        if (isset($data['data'])) {
            /** @noinspection JsonEncodingApiUsageInspection */
            $decodedData = json_decode($data['data'], true) ?? [];
            $additionalData['customAttributes'] = $decodedData['custom_attributes'] ?? [];
        }

        if ($additionalData === []) {
            return $plan;
        }

        return parent::hydrate($additionalData, $plan);
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        return parent::createEmptyInstance($className, $data);
    }

    /**
     * @param PlanReadModel $object
     * @return array
     */
    public function extract($object): array
    {
        return array_merge($this->planHydrator->extract($object), [
            'customAttributes' => $object->customAttributes,
        ]);
    }
}
