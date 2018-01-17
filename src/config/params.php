<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

use hiqdev\php\billing\price\EnumPrice;
use hiqdev\php\billing\price\SinglePrice;

return [
    'billing-hiapi.priceTypes' => [
        'certificate_purchase'  => EnumPrice::class,
        'certificate_renewal'   => EnumPrice::class,

        'ip_num'                => SinglePrice::class,
        'monthly'               => SinglePrice::class,
        'backup_du'             => SinglePrice::class,
        'server_traf'           => SinglePrice::class,
        'server_traf_in'        => SinglePrice::class,
        'server_traf_max'       => SinglePrice::class,
        'server_traf95'         => SinglePrice::class,
        'server_traf95_in'      => SinglePrice::class,
        'server_traf95_max'     => SinglePrice::class,
    ],
];
