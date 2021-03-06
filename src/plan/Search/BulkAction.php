<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiapi\Core\Auth\AuthRule;
use hiqdev\billing\hiapi\plan\AvailableFor;
use hiqdev\php\billing\plan\PlanRepositoryInterface;

class BulkAction
{
    /**
     * @var PlanRepositoryInterface
     */
    private $repo;

    public function __construct(PlanRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $spec = $command->getSpecification();
        if (empty($command->where[AvailableFor::SELLER_FIELD]) &&
            empty($command->where[AvailableFor::CLIENT_ID_FIELD])
        ) {
            AuthRule::currentUser()->applyToSpecification($spec);
        }
        $res = $this->repo->findAll($spec);

        return new ArrayCollection($res);
    }
}
