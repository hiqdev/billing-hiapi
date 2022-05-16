<?php

declare(strict_types=1);

namespace hiqdev\billing\hiapi\Hydrator\Strategy;

use InvalidArgumentException;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Money\Currency;
use Money\Money;

/**
 * Class MoneyStrategy converts between {@see Money} and array types.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class MoneyStrategy implements StrategyInterface
{
    /**
     * @param Money $value
     * @param object|null $object
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return [
            'currency'  => $value->getCurrency()->getCode(),
            'amount'    => $value->getAmount(),
        ];
    }

    /**
     * Converts `amount`, passed in cents to {@see Money} object.
     *
     * @param array $value
     * @psalm-param array{currency: string, amount: int|float|string} $value
     * @param array|null $data
     * @return Money
     */
    public function hydrate($value, ?array $data)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Money value must be an array');
        }

        if (!isset($value['currency'])) {
            throw new InvalidArgumentException('Money value must contain `currency` key');
        }

        $amount = sprintf('%.0f', $value['amount'] ?? '0');
        $currency = new Currency(strtoupper($value['currency']));

        return new Money($amount, $currency);
    }
}
