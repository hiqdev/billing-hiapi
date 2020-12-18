<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\action\Calculate;

use hiqdev\php\billing\action\ActionInterface;
use Zend\Hydrator\HydratorInterface;

interface PaidCommandInterface
{
    /**
     * @param HydratorInterface $hydrator
     * @return ActionInterface
     */
    public function createAction(HydratorInterface $hydrator): ActionInterface;
}
