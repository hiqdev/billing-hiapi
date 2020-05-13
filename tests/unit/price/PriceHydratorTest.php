<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\price;

use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;
use Money\Currency;
use Money\Money;
use yii\helpers\Json;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PriceHydratorTest extends BaseHydratorTest
{
    const ID1   = '11111';
    const CUR1  = 'USD';
    const NAME1 = 'name-11111';
    const TYPE1 = 'server';
    const UNIT1 = 'MB';

    const ID2   = '22222';
    const CUR2  = 'EUR';
    const NAME2 = 'certificate_purchase';
    const TYPE2 = 'certificate';
    const UNIT2 = 'GB';

    protected $dataSinglePrice = [
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

    protected $dataEnumPrice = [
        'id' => self::ID2,
        'type' => [
            'id'        => self::ID2,
            'name'      => self::NAME2,
        ],
        'target' => [
            'id'        => self::ID2,
            'type'      => self::TYPE2,
            'name'      => self::NAME2,
        ],
        'prepaid' => [
            'unit'      => self::UNIT2,
        ],
        'price' => [
            'currency'  => self::CUR2,
        ],
    ];

    protected $sums = [
        1 => self::ID1,
        2 => self::ID2,
    ];

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
        $this->dataEnumPrice['data'] = Json::encode(['sums' => $this->sums]);
    }

    public function testHydrateNewSinglePrice()
    {
        $obj = $this->hydrator->hydrate($this->dataSinglePrice, PriceInterface::class);
        $this->checkSinglePrice($obj);
    }

    public function testHydrateExistingSinglePrice()
    {
        $type = new Type(self::ID2, self::NAME2);
        $target = new Target(self::ID2, self::TYPE2, self::NAME2);
        $price = new Money(self::ID2, new Currency(self::CUR2));
        $prepaid = Quantity::create(self::ID2, Unit::create(self::UNIT2));
        $obj = new SinglePrice(self::ID2, $type, $target, null, $prepaid, $price);
        $this->hydrator->hydrate($this->dataSinglePrice, $obj);
        $this->checkSinglePrice($obj);
    }

    public function checkSinglePrice($obj)
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

    public function testHydrateNewEnumPrice()
    {
        $obj = $this->hydrator->hydrate($this->dataEnumPrice, PriceInterface::class);
        $this->checkEnumPrice($obj);
    }

    public function testHydrateExistingEnumPrice()
    {
        $type = new Type(self::ID2, self::NAME2);
        $target = new Target(self::ID2, self::TYPE2, self::NAME2);
        $currency = new Currency(self::CUR2);
        $unit = Unit::create(self::UNIT2);
        $sums = array_reverse($this->sums);
        $obj = new EnumPrice(self::ID2, $type, $target, null, $unit, $currency, $sums);
        $this->hydrator->hydrate($this->dataEnumPrice, $obj);
        $this->checkEnumPrice($obj);
    }

    public function checkEnumPrice($obj)
    {
        $this->assertInstanceOf(EnumPrice::class, $obj);
        $this->assertSame(self::ID2,    $obj->getId());
        $this->assertSame($this->sums,  $obj->getSums());

        $type = $obj->getType();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame(self::ID2,    $type->getId());
        $this->assertSame(self::NAME2,  $type->getName());

        $target = $obj->getTarget();
        $this->assertInstanceOf(Target::class, $target);
        $this->assertSame(self::ID2,    $target->getId());
        $this->assertSame(self::TYPE2,  $target->getType());
        $this->assertSame(self::NAME2,  $target->getName());

        $unit = $obj->getUnit();
        $this->assertInstanceOf(Unit::class, $unit);
        $this->assertSame(self::UNIT2,  $unit->getName());

        $currency = $obj->getCurrency();
        $this->assertInstanceOf(Currency::class, $currency);
        $this->assertSame(self::CUR2,   $currency->getCode());
    }
}
