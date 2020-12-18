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

use hiapi\Core\Auth\AuthRule;
use hiapi\exceptions\domain\ValidationException;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use League\Tactician\Middleware;
use hiqdev\DataMapper\Query\Specification;

class TargetLoader implements Middleware
{
    private TargetRepositoryInterface $repo;

    public bool $isRequired = false;

    public function __construct(TargetRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->target)) {
            $command->target = $this->findTarget($command);
            if ($this->isRequired && $command->target === null) {
                throw new ValidationException(sprintf('Failed to find target'));
            }
        }

        return $next($command);
    }

    private function findTarget($command): ?TargetInterface
    {
        $cond = [AuthRule::currentUser()];

        if (!empty($command->target_id)) {
            $cond['id'] = $command->target_id;
        } elseif (!empty($command->target_type) && !empty($command->target_name)) {
            $cond['type'] = $command->target_type;
            $cond['name'] = $command->target_name;
        } else {
            return null;
        }

        $target = $this->repo->findOne((new Specification)->where($cond));
        if ($target === false) {
            return null;
        }

        return $target;
    }
}
