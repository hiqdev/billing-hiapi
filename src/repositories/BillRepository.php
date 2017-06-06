<?php

namespace hiqdev\billing\hiapi\repositories;

use DateTime;
use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\Type;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\BillFactoryInterface;
use hiqdev\php\units\Quantity;
use Money\Money;

class BillRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var BillFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        BillFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }

    public function create(array $row)
    {
        $row = $this->split($row);
        $row['type'] = $this->createEntity(Type::class, $row['type']);
        $row['time'] = new DateTime($row['time']);
        $row['quantity'] = Quantity::create('megabyte', $row['quantity']['quantity']);
        $row['sum'] = Money::USD($row['sum.amount']);
        $row['customer'] = $this->createEntity(Customer::class, $row['customer']);
        $row['target'] = $this->createEntity(Target::class, $row['target']);

        return parent::create($row);
    }

    protected function split(array $row)
    {
        foreach ($row as $key => $value) {
            $parts = explode('.', $key, 2);
            if (count($parts)>1) {
                $row[$parts[0]][$parts[1]] = $value;
                unset($row[$key]);
            }
        }

        return $row;
    }
}
