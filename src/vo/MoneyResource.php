<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\vo;

use hiapi\jsonApi\AttributionBasedResource;
use Money\Money;

class MoneyResource extends AttributionBasedResource
{
    /**
     * @param Money $entity
     */
    public function getId($entity): string
    {
        return (string)$entity->getAmount() . $entity->getCurrency();
    }

    public function getAttributes($entity): array
    {
        return [
            'amount' => fn(Money $po): string => (string) $po->getAmount(),
            'currency' => fn(Money $po): string => (string) $po->getCurrency()->getCode(),
        ];
    }
}
