<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\DataMapper\Attribute\DateTimeAttribute;
use hiqdev\DataMapper\Attribute\IntegerAttribute;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class FeatureAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'starts' => DateTimeAttribute::class,
            'expires' => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [
            'target' => TargetAttribution::class,
            'type' => TypeAttribution::class,
        ];
    }
}
