<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\bill;

use hiapi\jsonApi\AttributionBasedResource;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\charge\ChargeInterface;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;

class BillResource extends AttributionBasedResource
{
    /**
     * @param BillInterface $entity
     */
    public function getRelationships($entity): array
    {
        $res = array_merge(parent::getRelationships($entity), [
            'charges' => fn (BillInterface $po) => ToManyRelationship::create()
                ->setData($po->getCharges(), $this->getResourceFor(ChargeInterface::class)),
        ]);

        // XXX temp because of Maximum nesting function level in woohoolabs/yin
        // TODO remove after fixing
        unset($res['plan']);

        return $res;
    }
}
