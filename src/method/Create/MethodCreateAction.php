<?php

namespace hiqdev\billing\hiapi\method\Create;

use hiqdev\billing\hiapi\method\MethodRepository;
use hiqdev\php\billing\method\Method;

class MethodCreateAction
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
