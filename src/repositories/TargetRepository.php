<?php

namespace hiqdev\billing\hiapi\repositories;

use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\target\TargetFactoryInterface;

class TargetRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var TargetFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        TargetFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }
}
