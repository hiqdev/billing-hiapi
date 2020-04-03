<?php

namespace hiqdev\billing\hiapi\provider\Prepare;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\method\Method;

final class MethodBulkCreate
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take($build->many(MethodCreateCommand::class))
                     ->checkPermission('method.create')
                     ->middlewares(
                         $build->repeat(MethodCreateAction::class)
                     )
                     ->return($build->many(Method::class));
    }
}
