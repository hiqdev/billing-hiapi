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
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\price\PriceInterface;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

/**
 * Class PlanHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class PlanHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Plan $object
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!empty($data['seller'])) {
            $data['seller'] = $this->hydrator->hydrate($data['seller'], Customer::class);
        }
        $raw_prices = $data['prices'];
        unset($data['prices']);

        /** @var Plan $plan */
        $plan = parent::hydrate($data, $object);

        if (is_array($raw_prices)) {
            $prices = [];
            foreach ($raw_prices as $key => $price) {
                if ($price instanceof PriceInterface) {
                    $price->setPlan($plan);
                    $prices[$key] = $price;
                } else {
                    $price['plan'] = $plan;
                    $prices[$key] = $this->hydrator->hydrate($price, PriceInterface::class);
                }
            }
            $plan->setPrices($prices);
        }

        return $plan;
    }

    /**
     * {@inheritdoc}
     * @param Plan $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'name'          => $object->getName(),
            'seller'        => $object->getSeller() ? $this->hydrator->extract($object->getSeller()) : null,
            'parent'        => $object->getParent() ? $this->hydrator->extract($object->getParent()) : null,
        ]);

        return $result;
    }
}
