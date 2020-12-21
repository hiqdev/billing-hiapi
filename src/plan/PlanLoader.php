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
use hiqdev\php\billing\customer\CustomerInterface;
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
            $command->plan = $this->findPlanByCommand($command);
            $this->validatePlan($command);
        }

        return $next($command);
    }

    public function findPlanByCommand($command): ?PlanInterface
    {
        if (!empty($command->plan_id)) {
            $availabilityFilter = [AvailableFor::CLIENT_ID_FIELD => $command->customer->getId()];
            return $this->findPlanById($command->plan_id, $availabilityFilter);
        }
        if (!empty($command->plan_name)) {
            return $this->findPlanByName($command->plan_name, $command->plan_seller ?? $this->getSeller($command));
        }
        if (!empty($command->plan_fullname)) {
            return $this->findPlanByFullName($command->plan_fullname);
        }

        return null;
    }

    private function findPlanById($id, array $availabilityFilter)
    {
        return $this->findPlanByArray($availabilityFilter + ['id' => $id]);
    }

    private function findPlanByFullName($fullname)
    {
        $ps = explode('@', $fullname, 2);
        if (empty($ps[1])) {
            return null;
        }
        return $this->findPlanByName($ps[0], $ps[1]);
    }

    private function findPlanByName($name, $seller)
    {
        return $this->findPlanByArray([
            'name' => $name,
            'seller' => $seller,
            AvailableFor::SELLER_FIELD => $seller,
        ]);
    }

    private function findPlanByArray(array $cond)
    {
        return $this->repo->findOne((new Specification)->where($cond)) ?: null;
    }

    private function getSeller($command): ?string
    {
        return $command->customer->getSeller()->getLogin();
    }

    private function validatePlan($command): void
    {
        if (!$this->isRequired || !empty($command->plan)) {
            return;
        }
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
