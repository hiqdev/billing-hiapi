<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\action\Calculate;

use hiqdev\php\billing\action\ActionInterface;

interface PaidCommandInterface
{
    /**
     * @return ActionInterface[]
     */
    public function getActions(): array;
}
