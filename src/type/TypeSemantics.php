<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\type;

use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;

final class TypeSemantics
{
    private const MONTHLY  = 'monthly';
    private const OVERUSE  = 'overuse';
    private const DISCOUNT = 'discount';
    private const DEPOSIT  = 'deposit';
    private const HARDWARE = 'hardware';

    /**
     * // TODO: Probably not the best place for this method
     */
    public function createMonthlyType(): TypeInterface
    {
        return new Type(null, self::MONTHLY . ',' . self::MONTHLY);
    }

    public function isMonthly(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::MONTHLY;
    }

    public function isHardware(TypeInterface $type): bool
    {
        return $this->localName($type) === self::HARDWARE;
    }

    public function isDiscount(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::DISCOUNT;
    }

    public function isOveruse(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::OVERUSE;
    }

    public function isDeposit(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::DEPOSIT;
    }

    public function groupName(TypeInterface $type): string
    {
        $name = $type->getName();
        if (strpos($name, ',') !== false) {
            [$name,] = explode(',', $name, 2);
        }

        return $name;
    }

    public function localName(TypeInterface $type): string
    {
        $name = $type->getName();
        if (strpos($name, ',') !== false) {
            [,$name] = explode(',', $name, 2);
        }

        return $name;
    }
}
