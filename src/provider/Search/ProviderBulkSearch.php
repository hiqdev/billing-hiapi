<?php

namespace hiqdev\billing\hiapi\provider\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\php\billing\provider\Provider;


final class ProviderBulkSearch
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->exportTo(Tenant::ALL)
                     ->take(ProviderSearchCommand::class)
                     ->checkPermission('provider.read')
                     ->middlewares(
                         $build->call(ProviderBulkSearchAction::class)
                     )
                     ->return($build->many(Provider::class));
    }
}
