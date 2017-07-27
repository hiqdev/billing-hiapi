<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories;

class PriceHydrator implements HydratorInterface
{
    /**
     * @var PriceFactory
     */
    private $priceFactory;

    public function __construct(PriceFactory $priceFactory)
    {
        $this->priceFactory = $priceFactory;
    }

    public function hydrateMultiple(array $data)
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = $this->priceFactory->createByDto(PriceCreationDto::fromArray($item));
        }

        return $result;
    }

    public function hydrate($object, array $data)
    {
    }
}
