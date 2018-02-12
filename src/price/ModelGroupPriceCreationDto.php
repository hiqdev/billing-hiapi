<?php

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\price\PriceCreationDto;
use Money\Money;

/**
 * Class ModelGroupPriceCreationDto
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ModelGroupPriceCreationDto extends PriceCreationDto
{
    /** @var Money[] */
    public $subprices = [];
}
