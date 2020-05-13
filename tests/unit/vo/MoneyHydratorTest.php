<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\vo;

use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;
use Money\Currency;
use Money\Money;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class MoneyHydratorTest extends BaseHydratorTest
{
    const AMOUNT1 = '11111';
    const CURRENCY1 = 'USD';

    const AMOUNT2 = '22222';
    const CURRENCY2 = 'EUR';

    protected $data = [
        'amount'    => self::AMOUNT1,
        'currency'  => self::CURRENCY1,
    ];

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
    }

    public function testHydrateNew()
    {
        $obj = $this->hydrator->hydrate($this->data, Money::class);
        $this->checkValues($obj);
    }

    public function testHydrateOld()
    {
        $obj = new Money(self::AMOUNT2, new Currency(self::CURRENCY2));
        $obj = $this->hydrator->hydrate($this->data, $obj);
        $this->checkValues($obj);
    }

    public function checkValues($obj)
    {
        $this->assertInstanceOf(Money::class, $obj);
        $this->assertSame(self::AMOUNT1, $obj->getAmount());
        $this->assertSame(self::CURRENCY1, $obj->getCurrency()->getCode());
    }
}
