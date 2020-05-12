<?php

namespace hiqdev\billing\hiapi\bill\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\bill\BillRepository;
use hiqdev\billing\mrdp\Infrastructure\Database\Condition\Auth\AuthRule;

class BillBulkSearchAction
{
    /**
     * @var BillRepository
     */
    private $repo;

    public function __construct(BillRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(BillSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll(
            AuthRule::currentUser()->applyToSpecification($command->getSpecification())
        );

        return new ArrayCollection($res);
    }
}
