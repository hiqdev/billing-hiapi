<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\type;

use hiqdev\billing\hiapi\models\Type;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

/**
 * Class TypeHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TypeHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Type $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'name'          => $object->getName(),
        ]);

        return $result;
    }
}
