<?php

namespace hiqdev\billing\hiapi\repositories;

use hiapi\components\ConnectionInterface;
use hiapi\query\QueryMutator;
use hiapi\query\Specification;
use yii\db\Query;

class PlanRepository extends \hiapi\repositories\BaseRepository
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

    public function __construct(ConnectionInterface $db, PlanFactory $planFactory, PlanHydrator $planHydrator, array $config = [])
    {
        parent::__construct($config);

        $this->db = $db;
        $this->planFactory = $planFactory;
        $this->planHydrator = $planHydrator;
    }

    public function findAll(Specification $specification)
    {
        $mutator = (new QueryMutator((new Query())
            ->select(['p.obj_id as id', 'p.name'])
            ->from('tariff p')
        ))->apply($specification);

        $rows = $mutator->getQuery()->createCommand($this->db)->queryAll();

        foreach ($specification->requestedRelations as $relation => $todo) {
            // todo...
        }

        $plans = $this->planHydrator->hydrateMultiple($this->planFactory->createPrototype(), $rows);

        return $plans;
    }
}
