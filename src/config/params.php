<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return [
    'billing-hiapi.priceTypes' => [
        'certificate_purchase'  => \hiqdev\php\billing\price\EnumPrice::class,
        'certificate_renewal'   => \hiqdev\php\billing\price\EnumPrice::class,
    ],
];
