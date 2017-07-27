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
use hiqdev\php\billing\customer\PriceFactoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use Money\Money;

class PriceRepository extends \hiapi\repositories\BaseRepository
{
    /**
     * @var PriceFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        PriceFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->db = $db;
        $this->factory = $factory;
    }

    public function create(array $row)
    {
        $row['type'] = $this->createEntity(Type::class, $row['type']);
        $row['target'] = $this->createEntity(Target::class, $row['target']);
        $currency = new Currency(strtoupper($row['price']['currency']));
        $row['price'] = new Money($row['price']['amount'], $currency);

        return parent::create($row);
    }
}
