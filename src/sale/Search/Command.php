<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\sale\Sale;

class Command extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Sale::class;
    }
}
