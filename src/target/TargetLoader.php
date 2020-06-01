<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use League\Tactician\Middleware;

class TargetLoader implements Middleware
{
    private $repo;

    public function __construct(TargetRepositoryInterface $repo)
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

    private function findTarget($command): ?TargetInterface
    {
        if (empty($command->target_id)) {
            return null;
        }

        return $this->repo->findById($command->target_id);
    }
}
