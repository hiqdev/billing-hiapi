<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Cancel;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\Multitenant\Tenant;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\feature\Feature;
use hiqdev\billing\hiapi\target\TargetLoader;
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
            ->description('Cancel a feature')
            ->exportTo(Tenant::ALL)
            ->take(Command::class)
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
                $build->call(Action::class)
            )
            ->return(Feature::class);
    }
}
