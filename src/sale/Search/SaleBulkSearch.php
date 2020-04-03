<?php

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\sale\Sale;


final class SaleBulkSearch
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take(SaleSearchCommand::class)
                     ->checkPermission('sale.read')
                     ->middlewares(
                         $build->call(SaleBulkSearchAction::class)
                     )
                     ->return($build->many(Sale::class));
    }
}
