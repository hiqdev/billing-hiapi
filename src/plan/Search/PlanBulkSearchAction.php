<?php

namespace hiqdev\billing\hiapi\plan\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\plan\PlanRepository;

class PlanBulkSearchAction
{
    /**
     * @var PlanRepository
     */
    private $repo;

    public function __construct(PlanRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(PlanSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
