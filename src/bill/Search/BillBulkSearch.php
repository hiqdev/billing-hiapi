<?php

namespace hiqdev\billing\hiapi\bill\Search;

use hiapi\endpoints\Module\Multitenant\Tenant;
use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiqdev\php\billing\bill\Bill;

final class BillBulkSearch
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
            ->exportTo(Tenant::ALL)
            ->take(BillSearchCommand::class)
            ->middlewares(
                $build->call(BillBulkSearchAction::class)
            )
            ->return($build->many(Bill::class))
        ;
    }
}
