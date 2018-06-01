<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\billing\hiapi\models\Plan;
use hiqdev\php\billing\price\PriceFactoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;
use Money\Currency;
use Money\Money;
use yii\helpers\Json;
use Zend\Hydrator\HydratorInterface;

/**
 * Class PriceHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PriceHydrator extends GeneratedHydrator
{
    /**
     * @var PriceFactoryInterface|PriceFactory
     */
    protected $priceFactory;

    public function __construct(
        HydratorInterface $hydrator,
        PriceFactoryInterface $priceFactory
    ) {
        parent::__construct($hydrator);
        $this->priceFactory = $priceFactory;
    }

    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function hydrate(array $row, $object)
    {
        $row['target'] = $this->hydrator->hydrate($row['target'], Target::class);
        $row['type'] = $this->hydrator->hydrate($row['type'], Type::class);
        if (isset($row['prepaid']['unit'])) {
            $row['unit'] = Unit::create($row['prepaid']['unit']);
        }
        if (isset($row['unit']) && isset($row['prepaid']['quantity'])) {
            $row['prepaid'] = Quantity::create($row['unit'], $row['prepaid']['quantity']);
        }
        if (isset($row['price']['currency'])) {
            $row['currency'] = new Currency(strtoupper($row['price']['currency']));
        }
        if (isset($row['currency']) && isset($row['price']['amount'])) {
            $row['price'] = new Money($row['price']['amount'], $row['currency']);
        }
        if (isset($row['data'])) {
            $data = is_array($row['data']) ? $row['data'] : Json::decode($row['data']);
        }
        $row['sums'] = empty($data['sums']) ? [] : $data['sums'];
        $row['subprices'] = $data['subprices'] ?? null;

        return parent::hydrate($row, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'name'          => $object->getName(),
            'parent_id'     => $object->parent->getid(),
            'seller_id'     => $object->seller->getid(),
        ]);

        return $result;
    }

    /**
     * @param string $className
     * @param array $data
     * @throws \ReflectionException
     * @return object
     */
    public function createEmptyInstance(string $className, array $data = [])
    {
        if (isset($data['data']) && !is_array($data['data'])) {
            $additionalData = Json::decode($data['data']);
        }

        $className = $this->priceFactory->findClassForTypes([
            $additionalData['class'] ?? null,
            $data['type']['name'],
        ]);

        return parent::createEmptyInstance($className, $data);
    }
}
