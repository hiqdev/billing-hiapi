<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\customer;

use hiqdev\php\billing\customer\Customer;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class CustomerHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class CustomerHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait;
    use GeneratedHydratorTrait {
        hydrate as generatedHydrate;
    }

    /**
     * {@inheritdoc}
     * @param object|Customer $object
     */
    public function hydrate(array $data, $object)
    {
        if (!empty($data['seller'])) {
            $data['seller'] = $this->hydrator->hydrate($data['seller'], Customer::class);
        }

        return $this->generatedHydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Customer $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId(),
            'login'         => $object->getLogin(),
            'seller_id'     => $object->seller->getid(),
        ]);

        return $result;
    }
}
