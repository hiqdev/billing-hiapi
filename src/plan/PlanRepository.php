<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\order\OrderInterface;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\yii\DataMapper\models\relations\Bucket;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use Yii;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = PlanQuery::class;

    /**
     * @param ActionInterface $action
     * @return PlanInterface
     */
    public function findByAction(ActionInterface $action)
    {
        $client_id = $action->getCustomer()->getId();
        $seller = $action->getCustomer()->getSeller()->getLogin();
        $type = $action->getTarget()->getType();

        $spec = Yii::createObject(Specification::class)
            ->with('prices')
            ->where([
                'type-name' => $type,
                'available_for' => [
                    'client_id' => $client_id,
                    'seller'    => $seller,
                ],
            ]);

        return $this->findOne($spec);
    }

    /**
     * @param OrderInterface $order
     * @return Plan[]|PlanInterface[]
     */
    public function findByOrder(OrderInterface $order)
    {
        return array_map([$this, 'findByAction'], $order->getActions());
    }

    public function findByIds(array $ids): array
    {
        $spec = Yii::createObject(Specification::class)
            ->with('prices')
            ->where(['id' => $ids]);

        return $this->findAll($spec);
    }

    protected function joinPrices(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'id');
        $spec = (new Specification())->where(['plan-id' => $bucket->getKeys()]);
        $prices = $this->getRepository(PriceInterface::class)->queryAll($spec);
        $bucket->fill($prices, 'plan.id', 'id');
        $bucket->pour($rows, 'prices');
    }
}
