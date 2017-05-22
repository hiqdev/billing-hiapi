<?php

namespace hiqdev\billing\hiapi\commands\plan;

use hiapi\commands\SearchCommand;

class SearchHandler extends \hiapi\commands\SearchHandler
{
    public function handle(SearchCommand $command)
    {
        return $command;
    }
}
