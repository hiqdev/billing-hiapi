<?php

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\plan\Plan;

class Command extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Plan::class;
    }
}
