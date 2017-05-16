<?php

namespace hiqdev\billing\hiapi\commands\order;

class CalculateOrderValueCommand extends OrderCommand
{
    protected $handlerClass = CalculateOrderValueHandler::class;
}
