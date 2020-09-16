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

use hiqdev\billing\hiapi\tools\CreditCheckerInterface;
use League\Tactician\Middleware;
use Zend\Hydrator\HydratorInterface;

class CheckCreditMiddleware implements Middleware
{
    private CreditCheckerInterface $checker;

    public function __construct(CreditCheckerInterface $checker, HydratorInterface $hydrator)
    {
        $this->checker = $checker;
        $this->hydrator = $hydrator;
    }

    public function execute($command, callable $next)
    {
        $action = $command->createAction($this->hydrator);
        $this->checker->check($action);

        return $next($command);
    }
}
