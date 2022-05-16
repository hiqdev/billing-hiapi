<?php

declare(strict_types=1);

namespace hiqdev\billing\hiapi\tests\unit\Hydrator\Strategy;

use hiqdev\billing\hiapi\Hydrator\Strategy\MoneyStrategy;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class MoneyStrategyTest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @covers \hiqdev\billing\hiapi\Hydrator\Strategy\MoneyStrategy
 */
class MoneyStrategyTest extends TestCase
{
    /**
     * @dataProvider hydrateHydrateDataProvider
     */
    public function testHydrate($array, $money): void
    {
        $strategy = new MoneyStrategy();
        $this->assertEquals($money, $strategy->hydrate($array, null));
    }

    public function testInputMustBeAnArray(): void
    {
        $strategy = new MoneyStrategy();
        $this->expectExceptionMessage('Money value must be an array');
        $strategy->hydrate('foo', null);
    }

    public function testCurrencyIsRequired(): void
    {
        $strategy = new MoneyStrategy();
        $this->expectExceptionMessage('Money value must contain `currency` key');
        $strategy->hydrate(['amount' => 100], null);
    }

    public function testExtract(): void
    {
        $strategy = new MoneyStrategy();
        $money = new Money(100, new Currency('USD'));
        $expected = [
            'amount' => 100,
            'currency' => 'USD',
        ];

        $this->assertEquals($expected, $strategy->extract($money, null));
    }

    public function hydrateHydrateDataProvider()
    {
        yield 'simple' => [
            'array' => [
                'amount' => '100',
                'currency' => 'USD',
            ],
            'object' => new Money(100, new Currency('USD'))
        ];

        yield 'lowercase currency' => [
            'array' => [
                'amount' => 100,
                'currency' => 'usd',
            ],
            'object' => new Money(100, new Currency('USD'))
        ];

        yield 'strategy expects amount in cents and rounds amount to integer (#1)' => [
            'array' => [
                'amount' => 100.99,
                'currency' => 'USD',
            ],
            'object' => new Money(101, new Currency('USD'))
        ];

        yield 'strategy expects amount in cents and rounds amount to integer (#2)' => [
            'array' => [
                'amount' => 100.4,
                'currency' => 'USD',
            ],
            'object' => new Money(100, new Currency('USD'))
        ];

        yield 'negative amount' => [
            'array' => [
                'amount' => -100,
                'currency' => 'USD',
            ],
            'object' => new Money(-100, new Currency('USD'))
        ];

        yield 'zero amount' => [
            'array' => [
                'amount' => 0,
                'currency' => 'USD',
            ],
            'object' => new Money(0, new Currency('USD'))
        ];

        yield 'missing amount' => [
            'array' => [
                'currency' => 'USD',
            ],
            'object' => new Money(0, new Currency('USD'))
        ];

        yield 'amount after precision lose' => [
            'array' => [
                'amount' => '70485.65' * 100,
                'currency' => 'USD',
            ],
            'object' => new Money(7048565, new Currency('USD'))
        ];
    }
}
