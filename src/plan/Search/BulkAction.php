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
use hiqdev\billing\hiapi\plan\PlanReadModelRepositoryInterface;

class BulkAction
{
    use SpecificationTrait;

    private PlanReadModelRepositoryInterface $repo;

    public function __construct(PlanReadModelRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $spec = $this->getSpecification($command);

        $res = $this->repo->findAll($spec);

        return new ArrayCollection($res);
    }
}
