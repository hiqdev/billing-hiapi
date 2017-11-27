<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiapi\db\CallExpression;
use hiapi\db\HstoreExpression;
use hiapi\components\ConnectionInterface;
use hiapi\components\EntityManagerInterface;
use hiapi\query\Specification;
use hiapi\repositories\BaseRepository;
use hiqdev\php\billing\charge\Charge;
use hiqdev\php\billing\charge\ChargeFactoryInterface;
use hiqdev\php\billing\sale\Sale;

class ChargeRepository extends BaseRepository
{
    public $queryClass = ChargeQuery::class;

    /**
     * @var ChargeFactory
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        EntityManagerInterface $em,
        ChargeFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->em = $em;
        $this->factory = $factory;
    }

    public function save(Charge $charge)
    {
        $action = $charge->getAction();
        $sale = new Sale(null, $action->getTarget(), $action->getCustomer(), $charge->getPrice()->getPlan());
        $action->setSale($sale);
        $this->em->save($action);
        $hstore = new HstoreExpression(array_filter([
            'id'        => $charge->getId(),
            'object_id' => $charge->getTarget()->getId(),
            'tariff_id' => $sale->getPlan()->getId(),
            'action_id' => $action->getId(),
            'type_id'   => $action->getType()->getId(),
            'type'      => $action->getType()->getName(),
            'buyer_id'  => $action->getCustomer()->getId(),
            'buyer'     => $action->getCustomer()->getLogin(),
            'currency'  => $charge->getSum()->getCurrency()->getCode(),
            'sum'       => $charge->getSum()->getAmount(),
            'quantity'  => $charge->getUsage()->getQuantity(),
            'sale_id'   => $sale ? $this->em->findId($sale) : null,
            'time'      => $charge->getTime(),
        ]));
        $call = new CallExpression('set_charge', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);
        $charge->setId($command->scalar());
    }
}
