<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\price;

use hiqdev\php\billing\price\PriceInterface;
use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\target\Target;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use Money\Currency;
use Money\Money;
use Yii;
use Zend\Hydrator\HydratorInterface;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PriceHydratorTest extends \PHPUnit\Framework\TestCase
{
    const ID1   = '11111';
    const CUR1  = 'USD';
    const NAME1 = 'name-11111';
    const TYPE1 = 'type-11111';
    const UNIT1 = 'MB';

    const ID2   = '22222';
    const CUR2  = 'EUR';
    const NAME2 = 'name-22222';
    const TYPE2 = 'type-22222';
    const UNIT2 = 'GB';

    protected $data = [
        'id' => self::ID1,
        'type' => [
            'id'        => self::ID1,
            'name'      => self::NAME1,
        ],
        'target' => [
            'id'        => self::ID1,
            'type'      => self::TYPE1,
            'name'      => self::NAME1,
        ],
        'prepaid' => [
            'quantity'  => self::ID1,
            'unit'      => self::UNIT1,
        ],
        'price' => [
            'amount'    => self::ID1,
            'currency'  => self::CUR1,
        ],
    ];

    public function setUp()
    {
        $this->hydrator = Yii::$container->get(HydratorInterface::class);
    }

    public function testHydrateNew()
    {
        $obj = $this->hydrator->hydrate($this->data, PriceInterface::class);
        $this->checkSimplePrice($obj);
    }

    public function testHydrateOld()
    {
        $type = new Type(self::ID2, self::NAME2);
        $target = new Target(self::ID2, self::TYPE2, self::NAME2);
        $price = new Money(self::ID2, new Currency(self::CUR2));
        $prepaid = Quantity::create(self::ID2, Unit::create(self::UNIT2));
        $obj = new SinglePrice(self::ID2, $type, $target, null, $prepaid, $price);
        $this->hydrator->hydrate($this->data, $obj);
        $this->checkSimplePrice($obj);
    }

    public function checkSimplePrice($obj)
    {
        $this->assertInstanceOf(SinglePrice::class, $obj);
        $this->assertSame(self::ID1,    $obj->getId());

        $type = $obj->getType();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame(self::ID1,    $type->getId());
        $this->assertSame(self::NAME1,  $type->getName());

        $target = $obj->getTarget();
        $this->assertInstanceOf(Target::class, $target);
        $this->assertSame(self::ID1,    $target->getId());
        $this->assertSame(self::TYPE1,  $target->getType());
        $this->assertSame(self::NAME1,  $target->getName());

        $prepaid = $obj->getPrepaid();
        $this->assertInstanceOf(Quantity::class, $prepaid);
        $this->assertSame(self::ID1,    $prepaid->getQuantity());
        $this->assertSame(self::UNIT1,  $prepaid->getUnit()->getName());

        $price = $obj->getPrice();
        $this->assertInstanceOf(Money::class, $price);
        $this->assertSame(self::ID1,    $price->getAmount());
        $this->assertSame(self::CUR1,   $price->getCurrency()->getCode());
    }
}
