<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\usage;

use hiqdev\billing\hiapi\target\TargetAttribution;
use hiqdev\billing\hiapi\type\TypeAttribution;
use hiqdev\billing\hiapi\vo\QuantityAttribution;
use hiqdev\DataMapper\Attribution\AbstractAttribution;

class UsageAttribution extends AbstractAttribution
{
    public function attributes()
    {
        return [];
    }

    public function relations()
    {
        return [
            'target' => TargetAttribution::class,
            'type' => TypeAttribution::class,
            'amount' => QuantityAttribution::class,
        ];
    }
}
