<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories;

use hiqdev\billing\hiapi\models\Price;
use hiqdev\php\billing\SinglePrice;
use hiqdev\php\billing\Target;
use hiqdev\php\billing\Type;
use hiqdev\php\units\Quantity;
use Money\Currency;
use Money\Money;

class PriceFactory
{
    protected $class = Price::class;

    public function createByDto(PriceCreationDto $dto)
    {
        return new SinglePrice(
            $dto->id,
            new Target($dto->target_id, new Type($dto->target_type_id, $dto->target_type_name)),
            new Type($dto->type_id, $dto->type),
            Quantity::create($dto->unit, $dto->quantity),
            new Money($dto->price, new Currency($dto->currency))
        );
    }
}
