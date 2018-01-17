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
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\bill\BillFactoryInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use Money\Currency;
use Money\Money;

class BillRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /**
     * @var BillFactoryInterface
     */
    protected $factory;

    public function __construct(
        EntityManagerInterface $em,
        BillFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->em = $em;
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
            'time'          => $bill->getTime()->format('c'),
            'is_finished'   => $bill->getIsFinished(),
            'increment'     => true,
        ]);
        $call = new CallExpression('set_bill2', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);
        $bill->setId($command->scalar());
        foreach ($bill->getCharges() as $charge) {
            $charge->setBill($bill);
            $this->em->save($charge);
        }
    }
}
