<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use League\Tactician\Middleware;

class PlanLoader implements Middleware
{
    private $repo;

    public function __construct(PlanRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->plan)) {
            $command->plan = $this->findPlan($command);
        }

        return $next($command);
    }

    private function findPlan($command): ?PlanInterface
    {
        if (empty($command->plan_id)) {
            return null;
        }

        return $this->repo->findById($command->plan_id);
    }
}
