<?php

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\plan\Plan;

class RatePriceHydrator extends PriceHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function extract($object)
    {
        return array_merge(
            parent::extract($object),
            array_filter(
                [
                    'prepaid' => $this->hydrator->extract($object->getPrepaid()),
//                    'price'   => $this->hydrator->extract($object->getPrice()),
                ],
                static function ($value): bool {
                    return $value !== null;
                },
                ARRAY_FILTER_USE_BOTH
            )
        );
    }
}
