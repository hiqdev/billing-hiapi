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
use hiqdev\billing\hiapi\plan\AvailableFor;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use yii\web\User;

class BulkAction
{
    private PlanRepositoryInterface $repo;
    private User $user;

    public function __construct(PlanRepositoryInterface $repo, User $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $spec = $command->getSpecification();

        if (empty($command->where[AvailableFor::SELLER_FIELD])) {
            $spec->where[AvailableFor::CLIENT_ID_FIELD] ??= $this->user->id;
        }

        $res = $this->repo->findAll($spec);

        return new ArrayCollection($res);
    }
}
