<?php

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\sale\Sale;

class SaleSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Sale::class;
    }
}
