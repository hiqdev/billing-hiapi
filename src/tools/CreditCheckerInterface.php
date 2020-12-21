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

use hiqdev\php\billing\action\ActionInterface;

interface CreditCheckerInterface
{
    /**
     * @param ActionInterface[] $action
     * @return bool
     */
    public function check(array $action): bool;
}
