<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

use DateTimeImmutable;
use hiqdev\php\billing\EntityInterface;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\TypeInterface;

interface FeatureInterface extends EntityInterface
{
    public function setId(int $int): void;

    public function target(): Target;

    public function type(): TypeInterface;

    public function starts(): DateTimeImmutable;

    public function expires(): ?DateTimeImmutable;

    public function setExpires(?DateTimeImmutable $expirationTime): void;
}
