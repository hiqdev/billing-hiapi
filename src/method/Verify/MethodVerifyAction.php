<?php

namespace hiqdev\billing\hiapi\method\Verify;

use hiqdev\billing\hiapi\method\Create\MethodCreateCommand;
use hiqdev\billing\hiapi\method\MethodRepository;
use hiqdev\php\billing\method\Method;

class MethodVerifyAction
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
