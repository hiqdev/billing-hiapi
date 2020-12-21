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

use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;

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

    public function createEmptyInstance(string $className, array $data = [])
    {
        if ($className === TypeInterface::class) {
            $className = Type::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
