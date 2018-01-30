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

use DateTimeImmutable;
use hiqdev\yii\DataMapper\models\relations\Bucket;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\order\OrderInterface;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\sale\SaleFactoryInterface;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\target\Target;
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

    public function create(array $row)
    {
        $row['target']      = $this->createEntity(Target::class, $row['target']);
        $row['customer']    = $this->createEntity(Customer::class, $row['customer']);
        $row['plan']        = $this->createEntity(Plan::class, $row['plan']);
        $row['time']        = new DateTimeImmutable($row['time']);

        return parent::create($row);
    }

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
            /// XXX how to pass if we want with prices into joinPlans?
            ->with('plans')
            ->where($cond);

        return $this->findOne($spec);
    }

    protected function joinPlans(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'plan-id');
        $spec = (new Specification())
            ->with('prices')
            ->where(['id' => $bucket->getKeys()]);
        $raw_plans = $this->getRepository(PlanInterface::class)->queryAll($spec);
        /// TODO for SilverFire: try to do with bucket
        $plans = [];
        foreach ($raw_plans as $plan) {
            $plans[$plan['id']] = $plan;
        }
        foreach ($rows as &$sale) {
            $sale['plan'] = $plans[$sale['plan-id']];
        }
    }
}
