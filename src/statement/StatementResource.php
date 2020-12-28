<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\statement;

use hiapi\jsonApi\AttributionBasedResource;
use hiqdev\php\billing\statement\Statement;
use hiqdev\php\billing\charge\ChargeInterface;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;

class StatementResource extends AttributionBasedResource
{
    /**
     * @param Statement $entity
     */
    public function getRelationships($entity): array
    {
        $res = array_merge(parent::getRelationships($entity), [
            'charges' => fn (Statement $po) => ToManyRelationship::create()
                ->setData($po->getCharges(), $this->getResourceFor(ChargeInterface::class)),
        ]);

        return $res;
    }
}
