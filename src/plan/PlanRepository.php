<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiapi\components\ConnectionInterface;
use hiapi\query\Specification;
use hiapi\repositories\BaseRepository;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\order\OrderInterface;
use hiqdev\php\billing\plan\PlanFactoryInterface;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use hiqdev\php\billing\plan\PriceInterface;
use Yii;

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
        $raw_prices = $row['prices'];
        unset($row['prices']);
        $plan = parent::create($row);
        if (is_array($raw_prices)) {
            $prices = [];
            foreach ($raw_prices as $key => $price) {
                $price['plan'] = $plan;
                $prices[$key] = $this->createEntity(PriceInterface::class, $price);
            }
            $plan->setPrices($prices);
        }

        return $plan;
    }

    public function findByAction(ActionInterface $action)
    {
        $client_id = $action->getCustomer()->getId();
        $seller = $action->getCustomer()->getSeller()->getLogin();
        $type = $action->getTarget()->getType();

        $spec = Yii::createObject(Specification::class)
            ->with(PriceInterface::class)
            ->where([
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
