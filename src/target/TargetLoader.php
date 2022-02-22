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
        if (!isset($command->target)) {
            $command->target = $this->findTarget($command);
            if ($this->isRequired && $command->target === null) {
                throw new ValidationException(sprintf('Failed to find target'));
            }
        }

        return $next($command);
    }

    public function findTarget($command): ?TargetInterface
    {
        if (!empty($command->target_id)) {
            return $this->findTargetById($command->target_id);
        }
        if (!empty($command->target_type) && !empty($command->target_name)) {
            return $this->findTargetByName($command->target_name, $command->target_type);
        }
        if (!empty($command->target_fullname)) {
            return $this->findTargetByFullName($command->target_fullname);
        }

        return null;
    }

    private function findTargetById($id)
    {
        return $this->findTargetByArray(['id' => $id]);
    }

    private function findTargetByFullName($fullname)
    {
        $ps = explode(':', $fullname, 2);
        if (empty($ps[1])) {
            return null;
        }
        return $this->findTargetByName($ps[1], $ps[0]);
    }

    private function findTargetByName($name, $type)
    {
        return $this->findTargetByArray([
            'name' => $name,
            'type' => $type,
        ]);
    }

    private function findTargetByArray(array $cond)
    {
        return $this->repo->findOne((new Specification)->where($cond)) ?: null;
    }
}
