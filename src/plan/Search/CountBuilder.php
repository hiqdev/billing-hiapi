<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\InOutControl\VO\Count;
use hiapi\endpoints\Module\Multitenant\Tenant;

final class CountBuilder
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
            ->description('Get plans count')
            ->exportTo(Tenant::ALL)
            ->take(Command::class)
            ->checkPermission('plan.read')
            ->middlewares(
                $build->call(CountAction::class)
            )
            ->return(Count::class);
    }
}
