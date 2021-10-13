<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\endpoints\Module\InOutControl\VO\Count;
use hiqdev\billing\hiapi\plan\PlanReadModelRepositoryInterface;

class CountAction
{
    use SpecificationTrait;

    private PlanReadModelRepositoryInterface $repo;

    public function __construct(PlanReadModelRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): Count
    {
        $specification = $this->getSpecification($command);

        $count = $this->repo->count($specification);

        return Count::is($count);
    }
}
