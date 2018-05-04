<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\type;

use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class TypeHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TypeHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait;
    use GeneratedHydratorTrait;

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
