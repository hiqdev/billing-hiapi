<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\support\order;

use hiqdev\billing\hiapi\charge\Generalizer;
use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\charge\GeneralizerInterface;

class SimpleCalculator extends \hiqdev\php\billing\tests\support\order\SimpleCalculator
{
    public function __construct(GeneralizerInterface $generalizer = null, $sale = null, $plan = null)
    {
        $generalizer = $generalizer ?: new Generalizer(new TypeSemantics());

        return parent::__construct($generalizer, $sale, $plan);
    }
}
