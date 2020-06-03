<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\vo;

use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Money\Currency;
use Money\Money;

/**
 * Class MoneyHydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class MoneyHydrator extends GeneratedHydrator
{
    public function hydrate(array $data, $object)
    {
        $currency = new Currency(strtoupper($data['currency']));

        return new Money($data['amount'], $currency);
    }

    /**
     * {@inheritdoc}
     * @param object|Money $object
     */
    public function extract($object)
    {
        return [
            'currency'  => $object->getCurrency()->getCode(),
            'amount'    => $object->getAmount(),
        ];
    }
}
