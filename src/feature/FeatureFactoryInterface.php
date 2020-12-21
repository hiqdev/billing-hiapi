<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

interface FeatureFactoryInterface
{
    public function create(FeatureDto $dto): FeatureInterface;
}
