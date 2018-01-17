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

use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
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
        EntityManagerInterface $em,
        ChargeFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->em = $em;
        $this->factory = $factory;
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
            'bill_id'   => $charge->getBill()->getId(),
            'time'      => $charge->getTime(),
        ]));
        $call = new CallExpression('set_charge', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);
        $charge->setId($command->scalar());
    }
}
