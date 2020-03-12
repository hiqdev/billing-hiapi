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
use hiqdev\php\billing\bill\BillRepositoryInterface;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\models\relations\Bucket;
use yii\db\ArrayExpression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class BillRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository implements BillRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = BillQuery::class;

    /**
     * @param BillInterface $bill
     */
    public function save(BillInterface $bill)
    {
        $hstore = $this->prepareHstore($bill);
        $this->db->transaction(function() use ($bill, $hstore) {
            $chargeIds = [];
            $call = new CallExpression('set_bill', [$hstore]);
            $command = (new Query())->select($call);
            $bill->setId($command->scalar($this->db));
            foreach ($bill->getCharges() as $charge) {
                $charge->setBill($bill);
                $this->em->save($charge);
                $chargeIds[] = $charge->getId();
            }
            if ($chargeIds) {
                $call = new CallExpression('set_bill_charges', [$bill->getId(), new ArrayExpression($chargeIds, 'integer')]);
                (new Query())->select($call)->scalar($this->db);
            }
        });
    }

    public function findId(BillInterface $bill)
    {
        if ($bill->getId()) {
            return $bill->getId();
        }

        $hstore = $this->prepareHstore($bill);
        $call = new CallExpression('bill_id', [$hstore]);

        return (new Query())->select($call)->scalar($this->db);
    }

    /**
     * undocumented function
     *
     * @return HstoreExpression
     */
    protected function prepareHstore(BillInterface $bill): HstoreExpression
    {
        return new HstoreExpression([
            'id'            => $bill->getId(),
            'object_id'     => $bill->getTarget()->getId(),
            'tariff_id'     => $bill->getPlan() ? $bill->getPlan()->getId() : null,
            'type_id'       => $bill->getType()->getId(),
            'type'          => $bill->getType()->getName(),
            'buyer_id'      => $bill->getCustomer()->getId(),
            'buyer'         => $bill->getCustomer()->getLogin(),
            'currency'      => $bill->getSum()->getCurrency()->getCode(),
            'sum'           => $bill->getSum()->getAmount(),
            'quantity'      => $bill->getQuantity()->getQuantity(),
            'unit'          => $bill->getQuantity()->getUnit()->getName(),
            'time'          => $bill->getTime()->format('c'),
            'label'         => $bill->getComment() ?: null,
            'is_finished'   => $bill->isFinished(),
        ]);
    }

    public function findByIds(array $ids): array
    {
        $spec = $this->createSpecification()
            ->with('charges')
            ->where(['id' => $ids]);

        return $this->findAll($spec);
    }

    protected function joinCharges(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'id');
        $spec = $this->createSpecification()->with('parent')->where(['bill-id' => $bucket->getKeys()]);
        $charges = $this->getRepository(ChargeInterface::class)->queryAll($spec);
        $bucket->fill($charges, 'bill.id', 'id');
        $bucket->pour($rows, 'charges');

        $indexedCharges = ArrayHelper::index($charges, 'id');
        foreach ($rows as &$bill) {
            foreach ($bill['charges'] as &$charge) {
                $charge = $this->enrichChargeWithParents($indexedCharges, $charge);
            }
        }
    }

    private function enrichChargeWithParents(array $charges, ?array $charge = null): array
    {
        if (isset($charge['parent-id'], $charges[$charge['parent-id']])) {
            $charge['parent'] = $this->enrichChargeWithParents($charges, $charges[$charge['parent-id']]);
        }

        return $charge;
    }
}
