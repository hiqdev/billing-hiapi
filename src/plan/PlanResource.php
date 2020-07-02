<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan;

use hiapi\jsonApi\AttributionBasedResource;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\price\PriceInterface;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;

class PlanResource extends AttributionBasedResource
{
    /**
     * @param PlanInterface $entity
     */
    public function getRelationships($entity): array
    {
        return array_merge(parent::getRelationships($entity), [
            'prices' => fn (PlanInterface $po) => ToManyRelationship::create()
                ->setData($po->getPrices(), $this->getResourceFor(PriceInterface::class)),
        ]);
    }
}
