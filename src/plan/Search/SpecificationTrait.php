<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan\Search;

use hiapi\Core\Auth\AuthRule;
use hiqdev\billing\hiapi\plan\AvailableFor;
use hiqdev\DataMapper\Query\Specification;

trait SpecificationTrait
{
    public function getSpecification(Command $command): Specification
    {
        $spec = $command->getSpecification();

        if (
            empty($spec->where[AvailableFor::CLIENT_ID_FIELD])
            && empty($spec->where[AvailableFor::SELLER_FIELD])
        ) {
            $spec = AuthRule::currentUser()
                ->canSeeSellerObjects()
                ->applyToSpecification($spec);
        }

        return $spec;
    }
}
