<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\customer;

use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;

/**
 * Class CustomerHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class CustomerHydrator extends GeneratedHydrator
{
    /**
     * {@inheritdoc}
     * @param object|Customer $object
     */
    public function hydrate(array $data, $object): object
    {
        if (!empty($data['seller'])) {
            $data['seller'] = $this->hydrator->hydrate($data['seller'], Customer::class);
        }

        return parent::hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|Customer $object
     */
    public function extract($object): array
    {
        return [
            'id'            => $object->getId(),
            'login'         => $object->getLogin(),
            'seller'        => $object->getSeller() ? $this->hydrator->extract($object->getSeller()) : null,
        ];
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        if ($className === CustomerInterface::class) {
            $className = Customer::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
