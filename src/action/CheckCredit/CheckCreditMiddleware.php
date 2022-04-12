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

namespace hiqdev\billing\hiapi\action\CheckCredit;

use hiqdev\billing\hiapi\action\Calculate\PaidCommandInterface;
use hiqdev\billing\hiapi\tools\CreditCheckerInterface;
use League\Tactician\Middleware;
use Laminas\Hydrator\HydratorInterface;

class CheckCreditMiddleware implements Middleware
{
    private CreditCheckerInterface $checker;

    public function __construct(CreditCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @param PaidCommandInterface $command
     * @param callable $next
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $actions = $command->getActions();
        $this->checker->check($actions);

        $this->reserveBalanceAmount();
        return $next($command);
    }

    private function reserveBalanceAmount(): void
    {
        // TODO: mutex to prevent concurrent charges?
    }
}
