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

use hiqdev\php\billing\bill\BillInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use yii\db\Query;

class BillRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /**
     * @param BillInterface $bill
     */
    public function save(BillInterface $bill)
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
        $this->db->transaction(function() use ($bill, $hstore) {
            $call = new CallExpression('set_bill', [$hstore]);
            $command = (new Query())->select($call);
            $bill->setId($command->scalar($this->db));
            foreach ($bill->getCharges() as $charge) {
                $charge->setBill($bill);
                $this->em->save($charge);
            }
        });
    }
}
