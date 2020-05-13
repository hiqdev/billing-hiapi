<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\type;

use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TypeSemanticsTest extends \PHPUnit\Framework\TestCase
{
    /** @var TypeSemantics */
    protected $semantics;

    public function setUp(): void
    {
        $this->semantics = new TypeSemantics();
    }

    /**
     * @dataProvider monthlyTypesProvider
     */
    public function testDetectesMonthlyTypes(TypeInterface $type, bool $shouldBeMonthly)
    {
        $this->assertSame($shouldBeMonthly, $this->semantics->isMonthly($type));
    }

    public function monthlyTypesProvider()
    {
        return [
            [new Type(null, 'monthly,monthly'), true],
            [new Type(null, 'monthly,foo,bar'), true],
            [new Type(null, 'monthly'), true],
            [new Type(null, 'overuse'), false],
            [new Type(null, 'overuse,monthly'), false],
        ];
    }
}
