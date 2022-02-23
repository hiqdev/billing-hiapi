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

class PerformBillingMiddleware implements Middleware
{
    public bool $checkCredit = true;
    private CreditCheckerInterface $creditChecker;
    private BillingInterface $billing;

    public function __construct(CreditCheckerInterface $creditChecker, BillingInterface $billing)
    {
        $this->creditChecker = $creditChecker;
        $this->billing = $billing;
    }

    /**
     * @param PaidCommandInterface $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $actions = $command->getActions();
        if ($this->checkCredit) {
            $this->creditChecker->check($actions);
        }
        $this->reserveBalanceAmount();

        $res = $next($command);

        $this->billing->perform($actions);

        return $res;
    }

    private function reserveBalanceAmount(): void
    {
        // TODO: mutex to prevent concurrent charges
    }
}
