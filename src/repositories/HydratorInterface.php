<?php


namespace hiqdev\billing\hiapi\repositories;


interface HydratorInterface
{
    public function hydrate($object, array $data);
}
