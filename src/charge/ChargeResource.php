<?php

declare(strict_types=1);

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\charge\ChargeInterface;
use hiapi\jsonApi\AttributionBasedResource;

class ChargeResource extends AttributionBasedResource
{
    /**
     * @param ChargeInterface $entity
     */
    public function getRelationships($entity): array
    {
        $res = parent::getRelationships($entity);

        // XXX temp because of Maximum nesting function level in woohoolabs/yin
        // TODO remove after fixing
        unset($res['bill']);

        return $res;
    }
}
