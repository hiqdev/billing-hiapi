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
use hiqdev\DataMapper\Query\Specification;

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
        $cond = [];
        if (!empty($command->plan_id)) {
            $cond['id'] = $command->plan_id;
        } elseif (!empty($command->plan_name) && !empty($command->plan_seller)) {
            $cond['name'] = $command->plan_name;
            $cond['seller'] = $command->plan_seller;
        } else {
            return null;
        }

        return $this->repo->findOne((new Specification)->where($cond));
    }
}
