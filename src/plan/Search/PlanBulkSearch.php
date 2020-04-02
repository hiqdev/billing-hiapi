<?php

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\plan\Plan;


final class PlanBulkSearch
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take(PlanSearchCommand::class)
                     ->checkPermission('plan.read')
                     ->middlewares(
                         $build->call(PlanBulkSearchAction::class)
                     )
                     ->return($build->many(Plan::class));
    }
}
