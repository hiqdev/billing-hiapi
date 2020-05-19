<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\vo;

use DateTimeImmutable;
use League\Tactician\Middleware;

class DateTimeLoader implements Middleware
{
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function execute($command, callable $next)
    {
        $name = $this->name;
        if (is_string($command->$name)) {
            $command->$name = new DateTimeImmutable($command->$name);
        }

        return $next($command);
    }
}
