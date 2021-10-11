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
use hiqdev\billing\hiapi\plan\PlanReadModelRepositoryInterface;
use yii\web\User;

class BulkAction
{
    private PlanReadModelRepositoryInterface $repo;
    private User $user;

    public function __construct(PlanReadModelRepositoryInterface $repo, User $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $spec = $command->getSpecification();

        if (
            empty($spec->where[AvailableFor::CLIENT_ID_FIELD])
            && empty($spec->where[AvailableFor::SELLER_FIELD])
        ) {
            $spec->where['client_id'] = AuthRule::clientId($this->user->id)->canSeeSellerObjects();
        }

        $res = $this->repo->findAll($spec);

        return new ArrayCollection($res);
    }
}
