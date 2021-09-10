<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\plan;

use hiqdev\DataMapper\Attribute\JsonAttribute;

class PlanReadModelAttribution extends PlanAttribution
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'data' => JsonAttribute::class,
        ]);
    }
}
