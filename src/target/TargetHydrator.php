<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

/**
 * Class TargetHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TargetHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Target $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'type'          => $object->getType(),
            'name'          => $object->getName(),
        ]);

        return $result;
    }
}
