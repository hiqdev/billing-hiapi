<?php

namespace hiqdev\billing\hiapi\method\Verify;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\method\Method;

final class MethodBulkVerify
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take($build->many(MethodVerifyCommand::class))
                     ->checkPermission('method.verify')
                     ->middlewares(
                         $build->repeat(MethodVerifyAction::class)
                     )
                     ->return($build->many(Method::class));
    }
}
