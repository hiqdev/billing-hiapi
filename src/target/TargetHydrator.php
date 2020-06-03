<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\billing\hiapi\models\Target;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Zend\Hydrator\HydratorInterface;

/**
 * Class TargetHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TargetHydrator extends GeneratedHydrator
{
    /**
     * @var TargetFactoryInterface
     */
    private $targetFactory;

    public function __construct(HydratorInterface $hydrator, TargetFactoryInterface $targetFactory)
    {
        parent::__construct($hydrator);
        $this->targetFactory = $targetFactory;
    }

    /**
     * {@inheritdoc}
     * @param object|Target $object
     */
    public function extract($object)
    {
        return [
            'id'            => $this->extractNone($object->getId()),
            'type'          => $this->extractNone($object->getType()),
            'name'          => $object->getName(),
        ];
    }

    protected function extractNone($value)
    {
        /**
         * XXX JSON doesn't support float INF and NAN
         * TODO think of it more.
         */
        return $value === TargetInterface::NONE ? '' : $value;
    }

    /** {@inheritdoc} */
    public function hydrate(array $data, $object)
    {
        if (!empty($data['type'])) {
            $data['type'] = $this->targetFactory->shortenType($data['type']);
        }

        return parent::hydrate($data, $object);
    }

    public function createEmptyInstance(string $className, array $data = [])
    {
        if (isset($data['type'])) {
            $className = $this->targetFactory->getClassForType($data['type']);
        }

        return parent::createEmptyInstance($className, $data);
    }
}
