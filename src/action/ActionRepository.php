<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action;

use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\action\ActionFactoryInterface;
use hiqdev\php\billing\action\ActionQuery;

class ActionRepository extends BaseRepository
{
    public $queryClass = ActionQuery::class;

    /**
     * @var ActionFactory
     */
    protected $factory;

    public function __construct(
        EntityManagerInterface $em,
        ActionFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->em = $em;
        $this->factory = $factory;
    }

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
            'time'      => $time ? $time->format('c') : null,
        ]));
        $call = new CallExpression('replace_action', [$action->getId(), $hstore]);
        $command = $this->em->getConnection()->createSelect($call);
        $action->setId($command->scalar());
    }
}
