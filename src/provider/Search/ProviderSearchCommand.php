<?php

namespace hiqdev\billing\hiapi\provider\Search;

use hiapi\commands\SearchCommand;
use hiqdev\php\billing\provider\Provider;

class ProviderSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Provider::class;
    }
}
