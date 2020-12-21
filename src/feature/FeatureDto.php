<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

use DateTimeImmutable;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;

class FeatureDto
{
    public $id;

    public $amount;

    public TypeInterface $type;

    public ?TargetInterface $target = null;

    public ?DateTimeImmutable $starts = null;
}
