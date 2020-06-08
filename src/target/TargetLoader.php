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
use hiqdev\DataMapper\Query\Specification;

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
        if (!empty($command->target_id)) {
            $cond = ['id' => $command->target_id];
        } elseif (!empty($command->target_type) && !empty($command->target_name)) {
            $cond = [
                'type' => $command->target_type,
                'name' => $command->target_name,
            ];
        } else {
            return null;
        }

        return $this->repo->findOne((new Specification)->where($cond));
    }
}
