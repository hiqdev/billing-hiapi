<?php

namespace hiqdev\billing\hiapi\commands\order;

class CalculateValueHandler
{
    public function handle(CalculateValueCommand $command)
    {
        var_dump($command);
        die(__METHOD__);
    }
}
