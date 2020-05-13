<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\method\Delete;

use hiqdev\billing\hiapi\method\Create\MethodCreateCommand;
use hiqdev\billing\hiapi\method\MethodRepository;
use hiqdev\php\billing\method\Method;

class MethodDeleteAction
{
    /**
     * @var MethodRepository
     */
    private $repo;

    public function __construct(MethodRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(MethodCreateCommand $command): Method
    {
    }
}
