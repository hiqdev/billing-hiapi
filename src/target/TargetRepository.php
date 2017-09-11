<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

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
