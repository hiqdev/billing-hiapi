<?php

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
