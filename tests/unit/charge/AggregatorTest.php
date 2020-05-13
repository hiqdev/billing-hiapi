<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\charge;

use hiqdev\billing\hiapi\charge\Generalizer;
use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\order\Order;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\tools\Aggregator;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;

class AggregatorTest extends \hiqdev\php\billing\tests\unit\tools\AggregatorTest
{
    /** @var Aggregator */
    protected $aggregator;

    public function setUp(): void
    {
        parent::setUp();

        $this->aggregator = new Aggregator(new Generalizer(new TypeSemantics()), new TypeSemantics());
    }

    public function testMonthlyChargesQuantityIsNotSummarized()
    {
        $this->markTestIncomplete('Test is strictly bound to certificate plan. TODO: implement for server');
        $actions = [
           new Action(
               null,
               new Type(null, 'monthly,monthly'),
               new Target(Target::ANY, 'server'),
               Quantity::items(0.25),
               $this->plan->customer,
               new \DateTimeImmutable()
           ),
           new Action(
               null,
               new Type(null, 'monthly,monthly'),
               new Target(Target::ANY, 'server'),
               Quantity::items(1),
               $this->plan->customer,
               new \DateTimeImmutable()
           ),
        ];

        $order = new Order(null, $this->plan->customer, $actions);
        $charges = $this->calculator->calculateOrder($order);

        // TODO: aggregate bills from charges
        // TODO: Check bill quantity is not summarized
    }
}
