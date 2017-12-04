<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories;

use DateTime;
use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\bill\BillFactoryInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use Money\Currency;
use Money\Money;

class BillRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var BillFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        BillFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }

    public function create(array $row)
    {
        $row['type'] = $this->createEntity(Type::class, $row['type']);
        $row['time'] = new DateTime($row['time']);
        $row['quantity'] = Quantity::create('megabyte', $row['quantity']['quantity']);
        $currency = new Currency(strtoupper($row['sum']['currency']));
        $row['sum'] = new Money($row['sum']['amount'], $currency);
        $row['customer'] = $this->createEntity(Customer::class, $row['customer']);
        $row['target'] = $this->createEntity(Target::class, $row['target']);

        return parent::create($row);
    }

    public function save(BillInterface $bill)
    {
        $chargeIds = [];
        foreach ($bill->getCharges() as $charge) {
            $this->em->save($charge);
            $chargeIds[] = $charge->getId();
        }
        $hstore = new HstoreExpression([
            'id'            => $bill->getId(),
            'object_id'     => $bill->getTarget()->getId(),
            'tariff_id'     => $bill->getPlan()->getId(),
            'type_id'       => $bill->getType()->getId(),
            'type'          => $bill->getType()->getName(),
            'buyer_id'      => $bill->getCustomer()->getId(),
            'buyer'         => $bill->getCustomer()->getLogin(),
            'currency'      => $bill->getSum()->getCurrency()->getCode(),
            'sum'           => $bill->getSum()->getAmount(),
            'quantity'      => $bill->getQuantity()->getQuantity(),
            'is_finished'   => $bill->getIsFinished(),
            'charge_ids'    => implode(',', $chargeIds),
        ]);
        $call = new CallExpression('set_bill', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);
        $bill->setId($command->scalar());
    }
}
