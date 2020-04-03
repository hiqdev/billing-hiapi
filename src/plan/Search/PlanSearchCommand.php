<?php

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\plan\Plan;

class PlanSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Plan::class;
    }
}
