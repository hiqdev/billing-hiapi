<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Purchase;

use hiqdev\billing\hiapi\action\Calculate\PaidCommand;
use hiqdev\billing\hiapi\type\TypeLoader;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;

final class Command extends PaidCommand
{
    public function getType(): TypeInterface
    {
        if ($this->type === null) {
            $this->type = $this->di->get(TypeLoader::class)->findPrefixed('type,feature', $this->type_name);
        }

        return $this->type;
    }

    protected function getActionType(): TypeInterface
    {
        return new Type(TypeInterface::ANY, $this->getType()->getName());
    }
}
