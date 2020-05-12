<?php

namespace hiqdev\billing\hiapi\bill\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\bill\Bill;

/**
 * Class BillSearchCommand
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class BillSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Bill::class;
    }
}
