<?php

namespace hiqdev\billing\hiapi\method\Delete;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\method\Method;

final class MethodBulkDelete
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take($build->many(MethodDeleteCommand::class))
                     ->checkPermission('method.delete')
                     ->middlewares(
                         $build->repeat(MethodDeleteAction::class)
                     )
                     ->return($build->many(Method::class));
    }
}
