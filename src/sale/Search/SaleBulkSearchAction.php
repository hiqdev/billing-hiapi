<?php

namespace hiqdev\billing\hiapi\sale\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\sale\SaleRepository;

class SaleBulkSearchAction
{
    /**
     * @var SaleRepository
     */
    private $repo;

    public function __construct(SaleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(SaleSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
