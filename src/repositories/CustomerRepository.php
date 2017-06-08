<?php

namespace hiqdev\billing\hiapi\repositories;

use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\customer\CustomerFactoryInterface;

class CustomerRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var CustomerFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        CustomerFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }

    public function create(array $row)
    {
        if (!empty($row['seller'])) {
            $row['seller'] = $this->create($row['seller']);
        }

        return parent::create($row);
    }

}
