<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan;

use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Zend\Hydrator\HydratorInterface;

/**
 * Class PlanReadModelHydrator hydrates {@see PlanReadModel}
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PlanReadModelHydrator extends GeneratedHydrator
{
    private PlanHydrator $planHydrator;

    public function __construct(HydratorInterface $hydrator, PlanHydrator $planHydrator)
    {
        parent::__construct($hydrator);

        $this->planHydrator = $planHydrator;
    }

    public function hydrate(array $data, $object)
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

    public function createEmptyInstance(string $className, array $data = [])
    {
        return parent::createEmptyInstance($className, $data);
    }

    public function extract($object)
    {
        return $this->planHydrator->extract($object);
    }
}
