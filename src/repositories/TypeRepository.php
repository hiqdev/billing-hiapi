<?php

namespace hiqdev\billing\hiapi\repositories;

use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\TypeFactoryInterface;

class TypeRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var TypeFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        TypeFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }
}
