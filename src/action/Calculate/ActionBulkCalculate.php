<?php

namespace hiqdev\billing\hiapi\action\Calculate;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\action\Action;

final class ActionBulkCalculate
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take($build->many(ActionCalculateCommand::class))
                     ->checkPermission('action.calculate')
                     ->middlewares()
                     ->return($build->many(Action::class));
    }
}
