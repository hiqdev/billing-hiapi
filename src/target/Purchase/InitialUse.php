<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\Purchase;

use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\Quantity;

final class InitialUse
{
    public TypeInterface $type;
    public Quantity $quantity;

    public function __construct(TypeInterface $type, Quantity $quantity)
    {
        $this->type = $type;
        $this->quantity = $quantity;
    }

    /**
     * @param string $type
     * @param string $unit
     * @param string $amount
     * @return static
     * @throw InvalidConfigException when passed $unit does not exist
     */
    public static function fromScalar(string $type, string $unit, string $amount): self
    {
        return new self(
            new Type(TypeInterface::ANY, $type),
            Quantity::create($unit, $amount)
        );
    }
}
