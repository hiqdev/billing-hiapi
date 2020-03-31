<?php

namespace hiqdev\billing\hiapi\sale\Create;

use hiapi\endpoints\Module\Multitenant\Tenant;
use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiqdev\php\billing\sale\Sale;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\plan\PlanLoader;
use hiqdev\billing\hiapi\target\TargetLoader;

final class SaleBulkCreate
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
            ->exportTo(Tenant::ALL)
            ->take($build->many(SaleCreateCommand::class))
            // XXX anybody can purchase? ->checkPermission('object.buy')
            ->middlewares(
                $build->repeat(CustomerLoader::class),
                $build->repeat(PlanLoader::class),
                $build->repeat(TargetLoader::class),
                $build->repeat(SaleCreateAction::class)
            )
            ->return($build->many(Sale::class))
        ;
    }
}
