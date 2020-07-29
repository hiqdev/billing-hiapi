<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\RatePrice;
use hiqdev\php\billing\price\SinglePrice;

/**
 * Class PriceFactory.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PriceFactory extends \hiqdev\php\billing\price\PriceFactory
{
    protected $creators = [
        SinglePrice::class => 'createSinglePrice',
        EnumPrice::class => 'createEnumPrice',
        RatePrice::class => 'createRatePrice',
        TemplatePrice::class => 'createTemplatePrice',
        RateTemplatePrice::class => 'createRateTemplatePrice',
    ];

    public function __construct(array $types = [], $defaultClass = null)
    {
        parent::__construct($types, $defaultClass);

        $this->types['TemplatePrice'] = TemplatePrice::class;
    }

    public function createRateTemplatePrice(RateTemplatePriceDto $dto): RateTemplatePrice
    {
        return new RateTemplatePrice($dto->id, $dto->type, $dto->target, $dto->plan, $dto->prepaid, $dto->price, $dto->rate);
    }
}
