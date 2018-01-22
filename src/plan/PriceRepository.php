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

use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\php\billing\price\PriceFactoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Unit;
use hiqdev\php\units\Quantity;
use hiqdev\yii2\collection\Model;
use Money\Currency;
use Money\Money;
use Money\Number;
use yii\helpers\Json;

class PriceRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    public $queryClass = PriceQuery::class;

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
        $row['target'] = $this->createEntity(Target::class, $row['target']);
        $row['type'] = $this->createEntity(Type::class, $row['type']);
        $row['unit'] = Unit::create($row['prepaid']['unit']);
        $row['prepaid'] = Quantity::create($row['unit'], $row['prepaid']['quantity']);
        $row['currency'] = new Currency(strtoupper($row['price']['currency']));
        $row['price'] = new Money($row['price']['amount'], $row['currency']);
        $data = Json::decode($row['data']);
        $row['sums'] = empty($data['sums']) ? [] : $data['sums'];

        return parent::create($row);
    }
}
