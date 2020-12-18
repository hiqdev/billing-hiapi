<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

use hiqdev\DataMapper\Query\Specification;
use hiqdev\DataMapper\Repository\RepositoryInterface;

/**
 * Interface FeatureRepositoryInterface
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @todo Should it extend RepositoryInterface?
 */
interface FeatureRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Specification $specification
     * @return false|Feature
     */
    public function findOne(Specification $specification);

    /**
     * Finds the $feature in DBMS by the unique constraints
     *
     * @param FeatureInterface $feature
     * @return FeatureInterface|null
     */
    public function findUnique(FeatureInterface $feature): ?FeatureInterface;
}
