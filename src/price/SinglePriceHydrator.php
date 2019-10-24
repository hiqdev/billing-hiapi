<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\plan\Plan;

/**
 * SinglePrice Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class SinglePriceHydrator extends PriceHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function extract($object)
    {
        return array_merge(parent::extract($object), array_filter([
            'prepaid'       => $this->hydrator->extract($object->getPrepaid()),
            'price'         => $this->hydrator->extract($object->getPrice()),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH));
    }
}
