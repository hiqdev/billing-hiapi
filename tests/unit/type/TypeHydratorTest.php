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

use hiqdev\php\billing\type\Type;
use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TypeHydratorTest extends BaseHydratorTest
{
    const ID1 = 11111;
    const NAME1 = 'login11111';

    const ID2 = 22222;
    const NAME2 = 'login22222';

    protected $data = [
        'id'        => self::ID1,
        'name'      => self::NAME1,
    ];

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
    }

    public function testHydrateNew()
    {
        $obj = $this->hydrator->hydrate($this->data, Type::class);
        $this->checkValues($obj);
    }

    public function testHydrateOld()
    {
        $obj = new Type(self::ID2, self::NAME2);
        $this->hydrator->hydrate($this->data, $obj);
        $this->checkValues($obj);
    }

    public function checkValues($obj)
    {
        $this->assertInstanceOf(Type::class, $obj);
        $this->assertSame(self::ID1, $obj->getId());
        $this->assertSame(self::NAME1, $obj->getName());
    }
}
