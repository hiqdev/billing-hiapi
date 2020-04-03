<?php

namespace hiqdev\billing\hiapi\action\Calculate;

use hiqdev\billing\hiapi\action\ActionRepository;
use hiqdev\php\billing\action\Action;

class ActionCalculateAction
{
    /**
     * @var ActionRepository
     */
    private $repo;

    public function __construct(ActionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(ActionCalculateCommand $command): Action
    {
    }
}
