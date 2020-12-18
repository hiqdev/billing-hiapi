<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Purchase;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\feature\Feature;
use hiqdev\billing\hiapi\target\TargetLoader;
use hiqdev\billing\hiapi\tools\PerformBillingMiddleware;
use hiqdev\billing\hiapi\type\TypeLoader;

final class Builder
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
            ->description('Purchase a feature')
            ->exportTo(Tenant::ALL)
            ->take(Command::class)
            ->checkPermission('have-goods')
            ->middlewares(
                CustomerLoader::class,
                [
                    '__class' => TargetLoader::class,
                    'isRequired' => true,
                ],
                [
                    '__class' => TypeLoader::class,
                    'typePrefix' => 'type,feature',
                ],
                PerformBillingMiddleware::class,
                $build->call(Action::class)
            )
            ->return(Feature::class);
    }
}
