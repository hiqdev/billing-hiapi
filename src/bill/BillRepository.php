<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use DateTime;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use Money\Currency;
use Money\Money;
use yii\db\Query;

class BillRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
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

    /**
     * XXX TO BE REMOVED.
     */
    public function saveReal(BillInterface $bill)
    {
        return $this->save($bill, true);
    }

    /**
     * @param BillInterface $bill
     * @param bool $isReal XXX TO BE REMOVED
     */
    public function save(BillInterface $bill, $isReal = false)
    {
        $hstore = new HstoreExpression([
            'id'            => $bill->getId(),
            'object_id'     => $bill->getTarget()->getId(),
            'tariff_id'     => $bill->getPlan() ? $bill->getPlan()->getId() : null,
            'type_id'       => $bill->getType()->getId(),
            'type'          => $bill->getType()->getName(),
            'buyer_id'      => $bill->getCustomer()->getId(),
            'buyer'         => $bill->getCustomer()->getLogin(),
            'currency'      => $bill->getSum()->getCurrency()->getCode(),
            'sum'           => $bill->getSum()->getAmount() * -1,
            'quantity'      => $bill->getQuantity()->getQuantity(),
            'time'          => $bill->getTime()->format('c'),
            'label'         => $bill->getComment() ?: null,
            'is_finished'   => $bill->isFinished(),
            'increment'     => true,
        ]);
        $call = new CallExpression('set_bill' . ($isReal ? '' : '2'), [$hstore]);
        $command = (new Query())->select($call);
        $bill->setId($command->scalar($this->db));
        foreach ($bill->getCharges() as $charge) {
            $charge->setBill($bill);
            $this->em->save($charge);
        }
    }
}
