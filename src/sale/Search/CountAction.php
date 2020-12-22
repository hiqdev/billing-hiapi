<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\Core\Auth\AuthRule;
use hiapi\endpoints\Module\InOutControl\VO\Count;
use hiqdev\php\billing\sale\SaleRepositoryInterface;

class CountAction
{
    private SaleRepositoryInterface $repo;

    public function __construct(SaleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): Count
    {
        $count = $this->repo->count(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );

        return Count::is($count);
    }
}
