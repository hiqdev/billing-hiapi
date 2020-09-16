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

namespace hiqdev\billing\hiapi\action\Calculate;

use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\action\ActionRepositoryInterface;

class Action
{
    private ActionRepositoryInterface $repo;

    public function __construct(ActionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ActionInterface
    {
    }
}
