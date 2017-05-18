<?php

namespace hiqdev\billing\hiapi\commands\order;

class PingCommand extends \hiapi\commands\Command
{
    protected static $handler = PingHandler::class;

    public $name;
    public $message;
    public $no;

    public function rules()
    {
        return [
            ['name', 'string', 'min' => 6],
            ['message', 'string'],
            ['no', 'number'],
            [['name', 'message'], 'required'],
        ];
    }
}
