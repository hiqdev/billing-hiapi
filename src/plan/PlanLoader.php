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

namespace hiqdev\billing\hiapi\plan;

use hiapi\exceptions\domain\RequiredInputException;
use hiapi\exceptions\domain\ValidationException;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use League\Tactician\Middleware;

class PlanLoader implements Middleware
{
    private PlanRepositoryInterface $repo;
    public bool $isRequired = false;

    public function __construct(PlanRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->plan)) {
            $command->plan = $this->findPlan($command);
            if ($this->isRequired && empty($command->plan)) {
                if (!empty($command->plan_id)) {
                    throw new ValidationException(
                        sprintf('Failed to find plan by ID %s: wrong ID or not authorized', $command->plan_id)
                    );
                }
                if (!empty($command->plan_name)) {
                    throw new ValidationException(
                        sprintf('Failed to find plan by name "%s": wrong name or not authorized', $command->plan_name)
                    );
                }

                throw new RequiredInputException('plan_id or plan_name');
            }
        }

        return $next($command);
    }

    private function findPlan($command): ?PlanInterface
    {
        $cond = [];
        if (!empty($command->plan_id)) {
            $cond['id'] = $command->plan_id;
        } elseif (!empty($command->plan_name)) {
            $cond['name'] = $command->plan_name;
            $cond['seller'] = $command->plan_seller ?? $this->getSeller($command);
        } else {
            return null;
        }

        return $this->repo->findOne((new Specification)->where($cond)) ?: null;
    }

    private function getSeller($command): ?string
    {
        return $command->customer->getSeller()->getLogin();
    }
}
