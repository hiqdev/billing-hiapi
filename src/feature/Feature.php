<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature;

use DateTimeImmutable;
use Exception;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\TypeInterface;

class Feature implements FeatureInterface
{
    /** @var int|string */
    protected $id;

    protected Target $target;

    protected DateTimeImmutable $starts;

    protected ?DateTimeImmutable $expires = null;

    private TypeInterface $type;

    public function __construct(
        $id,
        TypeInterface $type,
        Target $target,
        DateTimeImmutable $starts = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->target = $target;
        $this->starts = $starts ?? new DateTimeImmutable();
    }

    public function getId()
    {
        return $this->id;
    }

    public function target(): Target
    {
        return $this->target;
    }

    public function starts(): DateTimeImmutable
    {
        return $this->starts;
    }

    public function expires(): ?DateTimeImmutable
    {
        return $this->expires;
    }

    public function setExpires(?DateTimeImmutable $expires): void
    {
        $this->expires = $expires;
    }

    public function type(): TypeInterface
    {
        return $this->type;
    }

    public function setId(int $int): void
    {
        if ($this->id !== null) {
            throw new Exception('ID is already set');
        }

        $this->id = $int;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
