<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\Create;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\php\billing\target\Target;

final class Builder
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->description('Creates a new Target')
                     ->exportTo(Tenant::ALL)
                     ->take(Command::class)
                     ->checkPermission('plan.read')
                     ->middlewares(
                        CustomerLoader::class,
                        $build->call(Action::class)
                     )
                     ->return(Target::class);
    }
}
