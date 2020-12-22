<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\Core\Endpoint\BuilderFactory;
use hiapi\Core\Endpoint\Endpoint;
use hiapi\Core\Endpoint\EndpointBuilder;
use hiapi\endpoints\Module\InOutControl\VO\Count;
use hiapi\endpoints\Module\Multitenant\Tenant;

final class CountBuilder
{
    public function __invoke(BuilderFactory $build): Endpoint
    {
        return $this->create($build)->build();
    }

    public function create(BuilderFactory $build): EndpointBuilder
    {
        return $build->endpoint(self::class)
                     ->description('Get sales count')
                     ->exportTo(Tenant::ALL)
                     ->take(Command::class)
                     ->checkPermission('sale.read')
                     ->middlewares(
                         $build->call(CountAction::class)
                     )
                     ->return(Count::class);
    }
}
