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

namespace hiqdev\billing\hiapi\bill\GetInfo;

use hiapi\Core\Auth\AuthRule;
use hiqdev\php\billing\bill\BillRepositoryInterface;
use hiqdev\php\billing\bill\BillInterface;

final class Action
{
    private BillRepositoryInterface $repo;

    public function __construct(BillRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ?BillInterface
    {
        $res = $this->repo->findOne(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );

        return $res ?: null;
    }
}
