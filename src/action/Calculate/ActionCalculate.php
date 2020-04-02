<?php

namespace hiqdev\billing\hiapi\action\Calculate;

use hiapi\endpoints\Module\Multitenant\Tenant;
use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiqdev\php\billing\sale\Sale;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\plan\PlanLoader;
use hiqdev\billing\hiapi\target\TargetLoader;

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
                         CustomerLoader::class,
                         PlanLoader::class,
                         TargetLoader::class,
                         $build->call(SaleCreateAction::class)
                     )
                     ->return(Sale::class);
    }
}
