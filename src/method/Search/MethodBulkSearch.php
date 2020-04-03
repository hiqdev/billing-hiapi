<?php

namespace hiqdev\billing\hiapi\method\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\method\Method;


final class MethodBulkSearch
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take(MethodSearchCommand::class)
                     ->checkPermission('method.read')
                     ->middlewares(
                         $build->call(MethodBulkSearchAction::class)
                     )
                     ->return($build->many(Method::class));
    }
}
