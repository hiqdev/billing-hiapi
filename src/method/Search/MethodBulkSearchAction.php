<?php

namespace hiqdev\billing\hiapi\method\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\method\MethodRepository;

class MethodBulkSearchAction
{
    /**
     * @var MethodRepository
     */
    private $repo;

    public function __construct(MethodRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(MethodSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
