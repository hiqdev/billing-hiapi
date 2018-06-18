<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\customer;

use hiqdev\php\billing\customer\CustomerFactoryInterface;
use hiqdev\yii\DataMapper\components\ConnectionInterface;

class CustomerRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
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
