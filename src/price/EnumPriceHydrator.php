<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

/**
 * EnumPrice Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class EnumPriceHydrator extends PriceHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function extract($object)
    {
        return array_merge(parent::extract($object), array_filter([
            'unit'          => $this->hydrator->extract($object->getUnit()),
            'currency'      => $this->hydrator->extract($object->getCurrency()),
            'sums'          => $object->getSums(),
        ]));
    }
}
