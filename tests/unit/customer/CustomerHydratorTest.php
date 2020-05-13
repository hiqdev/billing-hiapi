<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\customer;

use hiqdev\php\billing\customer\Customer;
use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class CustomerHydratorTest extends BaseHydratorTest
{
    const ID1 = 11111;
    const LOGIN1 = 'login11111';

    const ID2 = 22222;
    const LOGIN2 = 'login22222';

    protected $data = [
        'id'        => self::ID1,
        'login'     => self::LOGIN1,
        'seller'    => [
            'id'        => self::ID2,
            'login'     => self::LOGIN2,
        ],
    ];

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
    }

    public function testHydrateNew()
    {
        $obj = $this->hydrator->hydrate($this->data, Customer::class);
        $this->checkValues($obj);
    }

    public function testHydrateOld()
    {
        $obj = new Customer(self::ID2, self::LOGIN2);
        $this->hydrator->hydrate($this->data, $obj);
        $this->checkValues($obj);
    }

    public function checkValues($obj)
    {
        $this->assertInstanceOf(Customer::class, $obj);
        $this->assertSame(self::ID1, $obj->getId());
        $this->assertSame(self::LOGIN1, $obj->getLogin());

        $seller = $obj->getSeller();
        $this->assertInstanceOf(Customer::class, $seller);
        $this->assertSame(self::ID2, $seller->getId());
        $this->assertSame(self::LOGIN2, $seller->getLogin());
    }
}
