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

namespace hiqdev\billing\hiapi\statement\GetInfo;

use hiapi\Core\Auth\AuthRule;
use hiqdev\php\billing\statement\StatementRepositoryInterface;
use hiqdev\php\billing\statement\Statement;

final class Action
{
    private StatementRepositoryInterface $repo;

    public function __construct(StatementRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ?Statement
    {
        $res = $this->repo->findOne(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );

        return $res ?: null;
    }
}
