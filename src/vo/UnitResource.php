<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\vo;

use hiapi\jsonApi\AttributionBasedResource;
use hiqdev\php\units\UnitInterface;

class UnitResource extends AttributionBasedResource
{
    /**
     * @param UnitInterface $entity
     */
    public function getId($entity): string
    {
        return (string)$entity->getName();
    }
}
