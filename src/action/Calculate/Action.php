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

use hiqdev\billing\mrdp\Action\ActionRepository;
use hiqdev\php\billing\action\ActionInterface;

class Action
{
    private ActionRepository $repo;

    public function __construct(ActionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(Command $command): ActionInterface
    {
    }
}
