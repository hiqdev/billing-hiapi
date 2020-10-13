<?php
declare(strict_types=1);

/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target\GetInfo;

use hiapi\commands\GetInfoCommand;
use hiqdev\php\billing\target\Target;

class Command extends GetInfoCommand
{
    public function getEntityClass(): string
    {
        return Target::class;
    }
}
