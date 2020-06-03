<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use hiqdev\php\billing\bill\BillState;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

/**
 * BillState Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class BillStateHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object)
    {
        return BillState::fromString($data['state'] ?? reset($data));
    }

    /**
     * {@inheritdoc}
     * @param BillState $object
     */
    public function extract($object)
    {
        return $object->getName();
    }
}
