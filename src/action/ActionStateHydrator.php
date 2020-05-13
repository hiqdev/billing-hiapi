<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action;

use hiqdev\php\billing\action\ActionState;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

/**
 * ActionState Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ActionStateHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object)
    {
        return ActionState::fromString($data['state'] ?? reset($data));
    }

    /**
     * @param ActionState $object
     */
    public function extract($object)
    {
        return $object->getName();
    }
}
