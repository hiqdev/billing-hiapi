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

    public function findByAction(ActionInterface $action)
    {
        $client_id = $action->getCustomer()->getId();
        $seller = $action->getCustomer()->getSeller()->getLogin();
        $type = $action->getTarget()->getType();

        $spec = Yii::createObject(Specification::class)
            ->with(Price::class)
            ->where([
                'type-name' => $type,
                'available_for' => [
                    'client_id' => $client_id,
                    'seller'    => $seller,
                ],
            ])
        ;

        return $this->findOne($spec);
    }

    public function findByOrder(OrderInterface $order)
    {
        return array_map([$this, 'findByAction'], $order->getActions());
    }
}
