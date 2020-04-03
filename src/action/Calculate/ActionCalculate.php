<?php

namespace hiqdev\billing\hiapi\action\Calculate;

use hiapi\endpoints\Module\Multitenant\Tenant;
use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiqdev\php\billing\action\Action;

final class ActionCalculate
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take(ActionCalculateCommand::class)
                     ->checkPermission('action.calculate')
                     ->middlewares(
                         $build->call(ActionCalculateAction::class)
                     )
                     ->return(Action::class);
    }
}
