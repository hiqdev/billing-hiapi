<?php

namespace hiqdev\billing\hiapi\commands\order;

class PingHandler
{
    public function handle(PingCommand $command)
    {
        return [
            'name' => $command->name,
        ];
    }
}
