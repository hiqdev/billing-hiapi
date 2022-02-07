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

use hiqdev\php\billing\bill\BillRequisite;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

class BillRequisiteHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object)
    {
        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param BillRequisite $object
     */
    public function extract($object): array
    {
        return array_filter([
            'id' => $object->getId(),
            'name' => $object->getName(),
        ]);
    }
}
