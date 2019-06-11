<?php

namespace hiqdev\billing\hiapi\type;

use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;

final class TypeSemantics
{
    private const MONTHLY = 'monthly';
    private const OVERUSE = 'overuse';
    private const DISCOUNT = 'discount';
    private const DEPOSIT = 'deposit';

    /**
     * // TODO: Probably not the best place for this method
     *
     * @return TypeInterface
     */
    public function createMonthlyType(): TypeInterface
    {
        return new Type(null, self::MONTHLY . ',' . self::MONTHLY);
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isMonthly(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::MONTHLY;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isDiscount(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::DISCOUNT;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isOveruse(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::OVERUSE;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isDeposit(TypeInterface $type): bool
    {
        return $this->groupName($type) === self::DEPOSIT;
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
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
