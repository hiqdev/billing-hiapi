<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\price;

use hiapi\jsonApi\AttributionBasedResource;

class PriceResource extends AttributionBasedResource
{
    public function getRelationships($entity): array
    {
        $res = parent::getRelationships($entity);

        // XXX temp because of Maximum nesting function level in woohoolabs/yin
        // TODO remove after fixing
        unset($res['plan']);

        return $res;
    }
}
