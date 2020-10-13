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

namespace hiqdev\billing\hiapi\target\GetInfo;

use hiapi\Core\Auth\AuthRule;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\php\billing\target\TargetInterface;

final class Action
{
    private TargetRepositoryInterface $repo;

    public function __construct(TargetRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): TargetInterface
    {
        return $this->repo->findOne(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );
    }
}
