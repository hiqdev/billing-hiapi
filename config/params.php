<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\target\Target;

return [
    'billing-hiapi.price.types' => [],
    'billing-hiapi.price.defaultClass' => SinglePrice::class,

    'billing-hiapi.target.classmap' => [],
    'billing-hiapi.target.defaultClass' => Target::class,
];
