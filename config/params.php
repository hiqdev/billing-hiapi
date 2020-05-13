<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

use hiqdev\billing\hiapi\price\TemplatePrice;
use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\SinglePrice;

return [
    'billing-hiapi.price.types' => [
        'certificate_purchase'              => EnumPrice::class,
        'certificate_renewal'               => EnumPrice::class,
        'certificate,certificate_purchase'  => EnumPrice::class,
        'certificate,certificate_renewal'   => EnumPrice::class,
        \hiqdev\billing\hiapi\price\TemplatePriceDto::class => TemplatePrice::class,
    ],
    'billing-hiapi.price.defaultClass' => SinglePrice::class,
];
