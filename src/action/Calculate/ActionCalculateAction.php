<?php

namespace hiqdev\billing\hiapi\sale\Close;

use hiqdev\billing\hiapi\action\ActionRepository;
use hiqdev\billing\hiapi\action\Calculate\ActionCalculateCommand;
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
        $this->checkRequiredInput($command);
//        $action = new Action();
//         $this->repo->calculate($action);

//        return $action;
    }

    protected function checkRequiredInput(ActionCalculateCommand $command): void
    {
        if (empty($command->customer)) {
            throw new RequiredInputException('customer');
        }
        if (empty($command->target)) {
            throw new RequiredInputException('target');
        }
    }
}
