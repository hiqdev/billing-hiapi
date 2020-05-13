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
use hiqdev\yii\DataMapper\hydrator\GeneratedHydrator;

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
    public function hydrate(array $data, $object)
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
    public function extract($object)
    {
        return [
            'id'            => $object->getId(),
            'login'         => $object->getLogin(),
            'seller'        => $object->getSeller() ? $this->hydrator->extract($object->getSeller()) : null,
        ];
    }
}
