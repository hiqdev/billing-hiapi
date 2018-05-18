<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\SinglePrice;

/**
 * Class PriceFactory
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PriceFactory extends \hiqdev\php\billing\price\PriceFactory
{
    protected $creators = [
        SinglePrice::class => 'createSinglePrice',
        EnumPrice::class => 'createEnumPrice',
        TemplatePrice::class => 'createTemplatePrice',
    ];

    public function createTemplatePrice(TemplatePriceDto $dto): TemplatePrice
    {
        return new TemplatePrice($dto->id, $dto->type, $dto->target, $dto->plan, $dto->prepaid, $dto->price, $dto->subprices);
    }
}
