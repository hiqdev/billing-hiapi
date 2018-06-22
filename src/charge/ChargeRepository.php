<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\charge\Charge;
use hiqdev\php\billing\charge\GeneralizerInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use yii\db\Query;

class ChargeRepository extends BaseRepository
{
    /** @var GeneralizerInterface */
    protected $generalizer;

    public function __construct(
        ConnectionInterface $db,
        EntityManagerInterface $em,
        GeneralizerInterface $generalizer,
        array $config = []
    ) {
        parent::__construct($db, $em, $config);
        $this->generalizer = $generalizer;
    }

    public function save(Charge $charge)
    {
        $action = $charge->getAction();
        if ($action->hasSale($action)) {
            $sale = $action->getSale();
        } else {
            $sale = new Sale(null, $action->getTarget(), $action->getCustomer(), $charge->getPrice()->getPlan());
            $action->setSale($sale);
        }
        if ($action->getTarget()->getId()) {
            $this->em->save($action);
        }
        $target = $this->generalizer->lessGeneral($charge->getAction()->getTarget(), $charge->getPrice()->getTarget());
        $hstore = new HstoreExpression(array_filter([
            'id'            => $charge->getId(),
            'object_id'     => $target->getId(),
            'tariff_id'     => $sale->getPlan()->getId(),
            'action_id'     => $action->getId(),
            'type_id'       => $action->getType()->getId(),
            'type'          => $action->getType()->getName(),
            'buyer_id'      => $action->getCustomer()->getId(),
            'buyer'         => $action->getCustomer()->getLogin(),
            'currency'      => $charge->getSum()->getCurrency()->getCode(),
            'sum'           => $charge->getSum()->getAmount(),
            'quantity'      => $charge->getUsage()->getQuantity(),
            'bill_id'       => $charge->getBill()->getId(),
            'time'          => $charge->getAction()->getTime()->format('c'),
            'is_finished'   => $charge->isFinished(),
            'label'         => $charge->getComment() ?: null,
        ]));
        $call = new CallExpression('set_charge', [$hstore]);
        $command = (new Query())->select($call);
        $charge->setId($command->scalar($this->db));
    }
}
