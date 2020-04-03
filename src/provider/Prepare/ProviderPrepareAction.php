<?php

namespace hiqdev\billing\hiapi\provider\Prepare;

use hiqdev\billing\hiapi\provider\ProviderRepository;
use hiqdev\php\billing\provider\Provider;

class ProviderPrepareAction
{
    /**
     * @var ProviderRepository
     */
    private $repo;

    public function __construct(ProviderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(ProviderPrepareCommand $command): Provider
    {
    }
}
