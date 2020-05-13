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

use hiqdev\php\billing\action\ActionInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use yii\db\Query;

class ActionRepository extends BaseRepository
{
    public function save(ActionInterface $action)
    {
        $sale = $action->getSale();
        $time = $action->getTime();
        $hstore = new HstoreExpression(array_filter([
            'id'        => $action->getId(),
            'parent_id' => $action->hasParent() ? $action->getParent()->getId() : null,
            'object_id' => $action->getTarget()->getId(),
            'type'      => $action->getType()->getName(),
            'type_id'   => $action->getType()->getId(),
            'amount'    => $action->getQuantity()->getQuantity(),
            'sale_id'   => $sale ? $this->em->findId($sale) : null,
            'state'     => $action->getState() ? $action->getState()->getName() : null,
            'time'      => $time ? $time->format('c') : null,
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH));
        $call = new CallExpression('set_action', [$hstore]);
        $command = (new Query())->select($call);
        $action->setId($command->scalar($this->db));
    }
}
