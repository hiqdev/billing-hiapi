<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\billing\hiapi\formula\FormulaHydrationStrategy;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\price\AbstractPrice;
use hiqdev\php\billing\price\PriceFactoryInterface;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Money\Currency;
use Money\Money;
use yii\helpers\Json;

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

    public function __construct(PriceFactoryInterface $priceFactory, FormulaHydrationStrategy $formulaHydrationStrategy)
    {
        $this->priceFactory = $priceFactory;

        $this->addStrategy('modifier', new NullableStrategy(clone $formulaHydrationStrategy, true));
    }

    /**
     * {@inheritdoc}
     * @param object|PriceInterface|AbstractPrice $object
     */
    public function hydrate(array $row, $object)
    {
        $row['target'] = $this->hydrator->hydrate($row['target'] ?? [], Target::class);
        $row['type'] = $this->hydrator->hydrate($row['type'], Type::class);
        if (isset($row['prepaid']['unit'])) {
            $row['unit'] = Unit::create($row['prepaid']['unit']);
        }
        if (isset($row['unit'], $row['prepaid']['quantity'])) {
            $row['prepaid'] = Quantity::create($row['unit'], $row['prepaid']['quantity']);
        }
        if (isset($row['price']['currency'])) {
            $row['currency'] = new Currency(strtoupper($row['price']['currency']));
        }
        if (isset($row['currency'], $row['price']['amount'])) {
            $row['price'] = new Money(round($row['price']['amount']), $row['currency']);
        }
        if (!empty($row['plan'])) {
            $row['plan'] = $this->hydrator->create($row['plan'], Plan::class);
        }
        if (isset($row['data'])) {
            $data = is_array($row['data']) ? $row['data'] : Json::decode($row['data']);
        }
        $row['modifier'] = $this->hydrateValue('modifier', trim($data['formula'] ?? ''));

        $row['sums'] = empty($data['sums']) ? [] : $data['sums'];
        $row['rate'] = $data['rate'] ?? null;
        $row['subprices'] = $data['subprices'] ?? null;

        return parent::hydrate($row, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|PriceInterface|SinglePrice $object
     */
    public function extract($object): array
    {
        return array_filter([
            'id'            => $object->getId(),
            'type'          => $this->hydrator->extract($object->getType()),
            'target'        => $this->hydrator->extract($object->getTarget()),
            'plan'          => null,
        ]);
    }

    /**
     * @throws \ReflectionException
     * @return object
     */
    public function createEmptyInstance(string $className, array $data = []): object
    {
        if (isset($data['data']) && !is_array($data['data'])) {
            $additionalData = Json::decode($data['data']);
        }

        $class = $this->priceFactory->findClassForTypes([
            $additionalData['class'] ?? null,
            $data['type']['name'],
        ]);

        return parent::createEmptyInstance($class, $data);
    }
}
