<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\vo;

use hiapi\jsonApi\AttributionBasedResource;
use hiqdev\php\units\QuantityInterface;

class QuantityResource extends AttributionBasedResource
{
    /**
     * @param QuantityInterface $entity
     */
    public function getId($entity): string
    {
        return (string)$entity->getQuantity() . $entity->getUnit()->getName();
    }

    public function getAttributes($entity): array
    {
        return [
            'quantity' => fn(QuantityInterface $po): string => (string) $po->getQuantity(),
            'unit' => fn(QuantityInterface $po): string => (string) $po->getUnit()->getName(),
        ];
    }
}
