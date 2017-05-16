<?php

namespace hiqdev\billing\hiapi\commands\order;

class PingCommand extends \hiapi\commands\Command
{
    protected $handlerClass = PingHandler::class;

    public function rules()
    {
        return [
            ['name', 'string'],
            ['message', 'string'],
            [['name', 'message'], 'required'],
        ];
    }
}
