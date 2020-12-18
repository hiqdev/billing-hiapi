<?php
declare(strict_types=1);
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tools;

use hiqdev\billing\hiapi\action\Calculate\PaidCommandInterface;
use hiqdev\php\billing\order\BillingInterface;
use League\Tactician\Middleware;
use Zend\Hydrator\HydratorInterface;

class PerformBillingMiddleware implements Middleware
{
    private CreditCheckerInterface $checker;
    private HydratorInterface $hydrator;
    private BillingInterface $billing;

    public function __construct(CreditCheckerInterface $checker, HydratorInterface $hydrator, BillingInterface $billing)
    {
        $this->checker = $checker;
        $this->hydrator = $hydrator;
        $this->billing = $billing;
    }

    /**
     * @param PaidCommandInterface $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $action = $command->createAction($this->hydrator);
        $this->checker->check($action);
        $this->reserveBalanceAmount();

        $res = $next($command);

        $this->billing->perform($action);

        return $res;
    }

    private function reserveBalanceAmount(): void
    {
        // TODO: mutex to prevent concurrent charges
    }
}
