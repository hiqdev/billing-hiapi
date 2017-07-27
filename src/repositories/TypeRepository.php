<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories;

use hiapi\components\ConnectionInterface;
use hiqdev\php\billing\type\TypeFactoryInterface;

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
