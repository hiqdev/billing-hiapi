<?php

namespace hiqdev\billing\hiapi\repositories;

use DateTime;
use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\bill\BillFactoryInterface;
use hiqdev\php\units\Quantity;
use Money\Currency;
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
        $row['type'] = $this->createEntity(Type::class, $row['type']);
        $row['time'] = new DateTime($row['time']);
        $row['quantity'] = Quantity::create('megabyte', $row['quantity']['quantity']);
        $currency = new Currency(strtoupper($row['sum']['currency']));
        $row['sum'] = new Money($row['sum']['amount'], $currency);
        $row['customer'] = $this->createEntity(Customer::class, $row['customer']);
        $row['target'] = $this->createEntity(Target::class, $row['target']);

        return parent::create($row);
    }
}
