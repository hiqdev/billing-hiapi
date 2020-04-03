<?php

namespace hiqdev\billing\hiapi\provider\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\provider\ProviderRepository;

class ProviderBulkSearchAction
{
    /**
     * @var ProviderRepository
     */
    private $repo;

    public function __construct(ProviderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(ProviderSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
