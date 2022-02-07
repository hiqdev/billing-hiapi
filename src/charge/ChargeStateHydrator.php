<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\charge\ChargeState;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

/**
 * ChargeState Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ChargeStateHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object): object
    {
        return ChargeState::fromString($data['state'] ?? reset($data));
    }

    /**
     * {@inheritdoc}
     * @param ChargeState $object
     */
    public function extract($object)
    {
        return $object->getName();
    }
}
