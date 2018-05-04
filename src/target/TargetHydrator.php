<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class TargetHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TargetHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait;
    use GeneratedHydratorTrait {
        hydrate as generatedHydrate;
    }

    /**
     * {@inheritdoc}
     * @param object|Target $object
     */
    public function hydrate(array $data, $object)
    {
        return $this->generatedHydrate($data, $object);
    }

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
