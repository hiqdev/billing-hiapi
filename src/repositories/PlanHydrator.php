<?php

namespace hiqdev\billing\hiapi\repositories;


class PlanHydrator implements HydratorInterface
{
    public function hydrateMultiple($prototype, array $data)
    {
        $results = [];

        foreach ($data as $key => $item) {
            $results[$key] = $this->hydrate(clone $prototype, $item);
        }

        return $results;
    }

    public function hydrate($object, array $data)
    {
        $reflection = new \ReflectionObject($object);

        foreach ($data as $property => $value) {
            $this->setProperty($reflection, $object, $property, $value);
        }

        return $object;
    }

    protected function setProperty(\ReflectionObject $reflection, $object, $property, $value)
    {
        $property = $reflection->getProperty($property);

        if ($property->isPublic()) {
            $property->setValue($object, $value);
            return;
        }

        $property->setAccessible(true);
        $property->setValue($object, $value);
        $property->setAccessible(false);
    }
}
