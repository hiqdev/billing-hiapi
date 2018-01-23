<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiqdev\billing\hiapi\models\relations\Bucket;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\order\OrderInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\sale\SaleFactoryInterface;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\sale\Sale;
use Yii;

class SaleRepository extends BaseRepository implements SaleRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = SaleQuery::class;

    /**
     * @var SaleFactory
     */
    protected $factory;

    public function __construct(
        EntityManagerInterface $em,
        SaleFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->em = $em;
        $this->factory = $factory;
    }

    public function findId(SaleInterface $sale)
    {
        $hstore = new HstoreExpression(array_filter([
            'buyer'     => $sale->getCustomer()->getLogin(),
            'buyer_id'  => $sale->getCustomer()->getId(),
            'object_id' => $sale->getTarget()->getId(),
            'tariff_id' => $sale->getPlan()->getId(),
        ]));
        $call = new CallExpression('sale_id', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);

        return $command->scalar();
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
        $client_id = $action->getCustomer()->getId();
        //$seller_id = $action->getCustomer()->getSeller()->getId();
        $type = $action->getTarget()->getType();

        if ($type === 'certificate') {
            //// XXX tmp crutch
            $class_id = $this->em->db->createCommand("SELECT class_id('certificate')")->queryScalar();
            $cond = [
                'target-id' => $class_id,
                'customer-id' => $client_id,
            ];
        } else if ($type === 'server') {
            $cond = [
                'target-id' => $action->getTarget()->getId(),
                'customer-id' => $client_id,
            ];
        } else {
            throw new \Exception('not implemented for: ' . $type);
        }

        $spec = Yii::createObject(Specification::class)
            /// XXX how to pass with prices into joinPlans?
            ->with('plans')
            ->where($cond);

        return $this->findOne($spec);
    }

    protected function joinPlans(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'plan-id');
        $plans = $this->getRepository(PlanInterface::class)->findByIds($bucket->getKeys());
        $bucket->fill($plans, 'plan.id', 'id');
        $bucket->pour($rows, 'plans');
    }
}
