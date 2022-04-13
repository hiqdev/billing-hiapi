<?php

declare(strict_types=1);

/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2022, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\formula;

use hiqdev\php\billing\charge\ChargeModifier;
use hiqdev\php\billing\formula\FormulaEngine;
use hiqdev\php\billing\formula\FormulaEngineInterface;
use Laminas\Hydrator\Strategy\StrategyInterface;

class FormulaHydrationStrategy implements StrategyInterface
{
    /**
     * @var FormulaEngineInterface|FormulaEngine
     */
    protected $formulaEngine;

    public function __construct(FormulaEngineInterface $formulaEngine)
    {
        $this->formulaEngine = $formulaEngine;
    }

    public function hydrate($value, ?array $data): object
    {
        return $this->formulaEngine->build($value);
    }

    /**
     * @param ChargeModifier $value
     * @param object|null $object
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        throw new \LogicException('Not implemented');
    }
}
