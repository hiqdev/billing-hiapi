<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\price\PriceFactoryInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use hiqdev\php\units\Unit;
use hiqdev\php\units\Quantity;
use hiqdev\yii2\collection\Model;
use Money\Currency;
use Money\Money;
use Money\Number;
use yii\helpers\Json;
use Zend\Hydrator\HydratorInterface;

/**
 * Class PriceHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PriceHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait {
        __construct as rootHydratorAwareConstructor;
    }

    use GeneratedHydratorTrait {
        hydrate as generatedHydrate;
        createEmptyInstance AS generatedCreateEmptyInstance;
    }

    protected $priceFactory;

    public function __construct(
        HydratorInterface $hydrator,
        PriceFactoryInterface $priceFactory
    ) {
        $this->rootHydratorAwareConstructor($hydrator);
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
        $row['unit'] = Unit::create($row['prepaid']['unit']);
        $row['prepaid'] = Quantity::create($row['unit'], $row['prepaid']['quantity']);
        $row['currency'] = new Currency(strtoupper($row['price']['currency']));
        $row['price'] = new Money($row['price']['amount'], $row['currency']);
        $data = Json::decode($row['data']);
        $row['sums'] = empty($data['sums']) ? [] : $data['sums'];

        return $this->generatedHydrate($row, $object);
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
     * @return object
     * @throws \ReflectionException
     */
    public function createEmptyInstance(string $className, array $data)
    {
        $className = $this->priceFactory->findClassForTypes([$data['type']]);

        return $this->generatedCreateEmptyInstance($className, $data);
    }
}
