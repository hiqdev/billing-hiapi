<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\order\OrderInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\models\relations\Bucket;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use Yii;
use yii\db\Query;

class SaleRepository extends BaseRepository implements SaleRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = SaleQuery::class;

    public function findId(SaleInterface $sale)
    {
        if ($sale->hasId()) {
            return $sale->getId();
        }
        $hstore = new HstoreExpression(array_filter([
            'buyer'     => $sale->getCustomer()->getLogin(),
            'buyer_id'  => $sale->getCustomer()->getId(),
            'object_id' => $sale->getTarget()->getId(),
            'tariff_id' => $sale->getPlan()->getId(),
        ]));
        $call = new CallExpression('sale_id', [$hstore]);
        $command = (new Query())->select($call);

        return $command->scalar($this->db);
    }

    /**
     * @param OrderInterface $order
     * @return Sale[]|SaleInterface[]
     */
    public function findByOrder(OrderInterface $order)
    {
        return array_map([$this, 'findByAction'], $order->getActions());
    }

    /**
     * @param ActionInterface $action
     * @return SaleInterface
     */
    public function findByAction(ActionInterface $action)
    {
        $type = $action->getTarget()->getType();

        if ($type === 'certificate') {
            $target_id = new CallExpression('class_id', ['certificate']);
        } elseif ($type === 'domain') {
            $target_id = new CallExpression('class_id', ['zone']);
        } elseif ($type === 'server') {
            $target_id = $action->getTarget()->getId();
        } else {
            throw new \Exception('not implemented for: ' . $type);
        }

        $spec = Yii::createObject(Specification::class)
            /// XXX how to pass if we want with prices into joinPlans?
            ->with('plans')
            ->where($this->buildTargetCond($target_id, $action->getCustomer()));

        return $this->findOne($spec);
    }

    protected function buildTargetCond($target_id, CustomerInterface $client)
    {
        $client_id = $client->getId();
        if ($client_id) {
            $seller_id = null;
        } else {
            $seller_id = $client->getSeller()->getId();
            $client_id = $seller_id;
        }

        return array_filter([
            'target-id'     => $target_id,
            'customer-id'   => $client_id,
            'seller-id'     => $seller_id,
        ]);
    }

    protected function joinPlans(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'plan-id');
        $spec = (new Specification())
            ->with('prices')
            ->where(['id' => $bucket->getKeys()]);
        $raw_plans = $this->getRepository(PlanInterface::class)->queryAll($spec);
        $bucket->fill($raw_plans, 'id');
        $bucket->pourOneToOne($rows, 'plan');
    }

    /**
     * @param SaleInterface $sale
     */
    public function save(SaleInterface $sale)
    {
        $hstore = new HstoreExpression([
            'object_id'     => $sale->getTarget()->getId(),
            'contact_id'    => $sale->getCustomer()->getId(),
            'tariff_id'     => $sale->getPlan() ? $sale->getPlan()->getId() : null,
            'time'          => $sale->getTime()->format('c'),
        ]);
        $call = new CallExpression('sale_object', [$hstore]);
        $command = (new Query())->select($call);
        $sale->setId($command->scalar($this->db));
    }
}
