<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\target;

use hiqdev\php\billing\target\Target;
use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class TargetHydratorTest extends BaseHydratorTest
{
    const ID1 = 11111;
    const TYPE1 = 'server';
    const NAME1 = 'name-11111';

    const ID2 = 22222;
    const TYPE2 = 'certificate';
    const NAME2 = 'name-22222';

    protected $data = [
        'id'        => self::ID1,
        'type'      => self::TYPE1,
        'name'      => self::NAME1,
    ];

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
    }

    public function testHydrateNew()
    {
        $obj = $this->hydrator->hydrate($this->data, Target::class);
        $this->checkValues($obj);
    }

    public function testHydrateOld()
    {
        $obj = new Target(self::ID2, self::TYPE2, self::NAME2);
        $this->hydrator->hydrate($this->data, $obj);
        $this->checkValues($obj);
    }

    public function checkValues($obj)
    {
        $this->assertInstanceOf(Target::class, $obj);
        $this->assertSame(self::ID1, $obj->getId());
        $this->assertSame(self::TYPE1, $obj->getType());
        $this->assertSame(self::NAME1, $obj->getName());
    }
}
