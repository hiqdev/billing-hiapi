<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\formula;

use hiqdev\php\billing\formula\FormulaEngine;
use hiqdev\php\billing\formula\FormulaEngineInterface;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Zend\Hydrator\HydratorInterface;

/**
 * Formula Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FormulaHydrator extends GeneratedHydrator
{
    /**
     * @var FormulaEngineInterface|FormulaEngine
     */
    protected $formulaEngine;

    public function __construct(
        HydratorInterface $hydrator,
        FormulaEngineInterface $formulaEngine
    ) {
        parent::__construct($hydrator);
        $this->formulaEngine = $formulaEngine;
    }

    public function hydrate(array $data, $object)
    {
        /// XXX actual data setting in createEmptyInstance
        return $object;
    }

    /**
     * {@inheritdoc}
     * @param object $object
     */
    public function extract($object)
    {
        return array_filter([
            'text'  => (string) $object,
        ]);
    }

    /**
     * @throws \ReflectionException
     * @return object
     */
    public function createEmptyInstance(string $className, array $data = [])
    {
        $formula = $data['formula'] ?? reset($data);

        return $this->formulaEngine->build($formula);
    }
}
