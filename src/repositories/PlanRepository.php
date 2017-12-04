<?php

namespace hiqdev\billing\hiapi\repositories;

use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\yii\DataMapper\query\QueryMutator;
use hiqdev\yii\DataMapper\query\Specification;
use yii\db\Query;

class PlanRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /**
     * @var ConnectionInterface
     */
    private $db;
    /**
     * @var PlanFactory
     */
    private $planFactory;
    /**
     * @var PlanHydrator
     */
    private $planHydrator;
    /**
     * @var PriceHydrator
     */
    private $priceHydrator;

    public function __construct(
        ConnectionInterface $db,
        PlanFactory $planFactory,
        PlanHydrator $planHydrator,
        PriceHydrator $priceHydrator,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->planFactory = $planFactory;
        $this->planHydrator = $planHydrator;
        $this->priceHydrator = $priceHydrator;
    }

    public function findAll(Specification $specification)
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
}
