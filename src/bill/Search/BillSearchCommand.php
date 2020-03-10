<?php

namespace hiqdev\billing\hiapi\bill\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\bill\Bill;

class BillSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Bill::class;
    }
}
