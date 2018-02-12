<?php

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\SinglePrice;

class PriceFactory extends \hiqdev\php\billing\price\PriceFactory
{
    protected $creators = [
        SinglePrice::class => 'createSinglePrice',
        EnumPrice::class => 'createEnumPrice',
        ModelGroupPrice::class => 'createModelGroupPrice',
    ];

    public function createModelGroupPrice(ModelGroupPriceCreationDto $dto): ModelGroupPrice
    {
        return new ModelGroupPrice($dto->id, $dto->type, $dto->target, $dto->plan, $dto->prepaid, $dto->price, $dto->subprices);
    }
}
