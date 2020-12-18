<?php

namespace hiqdev\billing\hiapi\feature;

use DateTimeImmutable;

interface FeatureServiceInterface
{
    /**
     * @param FeatureInterface $feature
     * @param int|float|string $periodsNumber the number of periods expiration must be calculated for
     * @return DateTimeImmutable|null
     */
    public function calculateExpiration(FeatureInterface $feature, $periodsNumber): ?DateTimeImmutable;

    /**
     * @param FeatureInterface $feature
     * @return mixed
     */
    public function activate(FeatureInterface $feature);
}
