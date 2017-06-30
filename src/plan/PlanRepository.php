<?php

namespace hiqdev\billing\hiapi\plan;

use hiapi\components\ConnectionInterface;
use hiapi\query\Specification;
use hiapi\repositories\BaseRepository;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\PlanFactoryInterface;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\order\OrderInterface;
use Yii;
use yii\db\Query;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface
{
    public $queryClass = PlanQuery::class;

    /**
     * @var PlanFactory
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        PlanFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }

    public function create(array $row)
    {
        $row['seller'] = $this->createEntity(Customer::class, $row['seller']);

        return parent::create($row);
    }

    public function old___findAll(Specification $specification)
    {
        $mutator = (new QueryMutator((new Query())
            ->select(['p.obj_id as id', 'p.name'])
            ->from('tariff p')
        ))->apply($specification);

        $rows = $mutator->getQuery()->createCommand($this->db)->queryAll();
        $this->extendWithPrices($rows);

        $plans = $this->planHydrator->hydrateMultiple($this->planFactory->createPrototype(), $rows);

        return $plans;
    }

    private function extendWithPrices(&$rows)
    {
        $tariff_ids = array_column($rows, 'id');

        $pricesRows = (new Query())
            ->select([
                'tr.id', 'tr.tariff_id',
                'tr.object_id as target_id', 'ob.label as target_name', 'tt.obj_id as target_type_id', 'tt.name as target_type_name',
                'rt.obj_id as type_id', 'rt.name as type',
                'tr.quantity', 'tu.name as unit',
                'cu.name as currency', 'round(tr.price)' // todo: do not round
            ])
            ->from('tariff_resource tr')
            ->leftJoin('zref rt', 'rt.obj_id = tr.type_id')
            ->leftJoin('obj ob', 'ob.obj_id = tr.object_id')
            ->leftJoin('zref tt', 'tt.obj_id = ob.class_id')
            ->leftJoin('zref tu', 'tu.obj_id = tr.unit_id')
            ->leftJoin('zref cu', 'cu.obj_id = tr.currency_id')
            ->where(['tr.tariff_id' => $tariff_ids])
            ->createCommand($this->db)->queryAll();

        foreach ($rows as &$plan) {
            $plan['prices'] = $this->priceHydrator->hydrateMultiple(array_filter($pricesRows, function ($row) use ($plan) {
                return $row['tariff_id'] === $plan['id'];
            }));
        }
    }

    public function findByAction(ActionInterface $action)
    {
        $client_id = $action->getCustomer()->getId();
        $seller = $action->getCustomer()->getSeller()->getLogin();
        $type = $action->getTarget()->getType();

        $spec = Yii::createObject(Specification::class)->where([
            'type-name' => $type,
            'available_for' => [
                'client_id' => $client_id,
                'seller'    => $seller,
            ],
        ]);

        return $this->findOne($spec);
    }

    public function findByOrder(OrderInterface $order)
    {
        return array_map([$this, 'findByAction'], $order->getActions());
    }
}
