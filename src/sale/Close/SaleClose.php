<?php

namespace hiqdev\billing\hiapi\sale\Close;

use hiapi\endpoints\Module\Multitenant\Tenant;
use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiqdev\php\billing\sale\Sale;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\plan\PlanLoader;
use hiqdev\billing\hiapi\target\TargetLoader;

final class SaleClose
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
            ->exportTo(Tenant::ALL)
            ->take(SaleCloseCommand::class)
            // XXX anybody can purchase? ->checkPermission('object.buy')
            ->middlewares(
                CustomerLoader::class,
                PlanLoader::class,
                TargetLoader::class,
                $build->call(SaleCloseAction::class)
            )
            ->return(Sale::class)
        ;
    }
}
