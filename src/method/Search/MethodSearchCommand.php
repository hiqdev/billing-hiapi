<?php

namespace hiqdev\billing\hiapi\method\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\method\Method;

class MethodSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Method::class;
    }
}
