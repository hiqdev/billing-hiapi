<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\action\Calculate;

class BulkPaidCommand implements PaidCommandInterface
{
    private iterable $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    public function getActions(): array
    {
        $res = [];
        foreach ($this->commands as $key => $command) {
            $res[$key] = $command->getAction();
        }

        return $res;
    }
}
