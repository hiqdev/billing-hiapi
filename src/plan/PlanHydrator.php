<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class PlanHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PlanHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait;

    use GeneratedHydratorTrait {
        hydrate as generatedHydrate;
    }

    /**
     * {@inheritdoc}
     * @param object|Plan $object
     */
    public function hydrate(array $data, $object)
    {
        $data['seller'] = $this->hydrator->hydrate($data['seller'], Customer::class);
        $raw_prices = $data['prices'];
        unset($data['prices']);

        /** @var Plan $plan */
        $plan = $this->generatedHydrate($data, $object);

        if (is_array($raw_prices)) {
            $prices = [];
            foreach ($raw_prices as $key => $price) {
                $price['plan'] = $plan;
                $prices[$key] = $this->hydrator->hydrate($price, PriceInterface::class);
            }
            $plan->setPrices($prices);
        }

        return $plan;
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
}
