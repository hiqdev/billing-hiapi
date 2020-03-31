<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use League\Tactician\Middleware;
use hiqdev\php\billing\target\Target;
use hiqdev\billing\hiapi\target\TargetRepository;

class TargetLoader implements Middleware
{
    private $repo;

    public function __construct(TargetRepository $repo)
    {
        $this->repo = $repo;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->target)) {
            $command->target = $this->findTarget($command);
        }

        return $next($command);
    }

    private function findTarget($command): ?Target
    {
        if (empty($command->target_id)) {
            return null;
        }

        return $this->repo->findById($command->target_id);
    }
}
