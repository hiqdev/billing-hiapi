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

namespace hiqdev\billing\hiapi\statement\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiapi\Core\Auth\AuthRule;
use hiqdev\php\billing\statement\StatementRepositoryInterface;

class Action
{
    private StatementRepositoryInterface $repo;

    public function __construct(StatementRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $res = $this->repo->findAll(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );

        return new ArrayCollection($res);
    }
}
