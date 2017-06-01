<?php

namespace hiqdev\billing\hiapi\repositories;

class PriceHydrator implements HydratorInterface
{
    /**
     * @var PriceFactory
     */
    private $priceFactory;

    function __construct(PriceFactory $priceFactory)
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
