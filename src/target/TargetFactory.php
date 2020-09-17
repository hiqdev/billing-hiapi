<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetCreationDto;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\units\exceptions\InvalidConfigException;

class TargetFactory implements TargetFactoryInterface
{
    protected array $classmap;

    protected string $defaultClass;

    public function __construct(array $classmap = [], string $defaultClass = Target::class)
    {
        $this->classmap = $classmap;
        $this->defaultClass = $defaultClass;
    }

    public function getEntityClassName(): string
    {
        return Target::class;
    }

    public function create(TargetCreationDto $dto): ?Target
    {
        if (!isset($dto->type)) {
            $class = Target::class;
        } else {
            $class = $this->getClassForType($dto->type);
            $dto->type = $this->shortenType($dto->type);
        }

        $target = new $class($dto->id, $dto->type, $dto->name);
        if ($target instanceof RemoteTarget) {
            $this->initRemoteTarget($target, $dto);
        }

        return $target;
    }

    // XXX tmp solution TODO redo better
    protected function initRemoteTarget($target, TargetCreationDto $dto): void
    {
        if (!empty($dto->customer)) {
            $target->customer = $dto->customer;
        }
        if (!empty($dto->remoteid)) {
            $target->remoteid = $dto->remoteid;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function shortenType(string $type): string
    {
        return $this->parseType($type)[0];
    }

    protected function parseType(string $type): array
    {
        if (strpos($type, '.') !== false) {
            return explode('.', $type, 2);
        }

        return [$type, '*'];
    }

    /**
     * {@inheritdoc}
     */
    public function getClassForType(string $type): string
    {
        [$type, $subtype] = $this->parseType($type);
        $class = $this->classmap[$type][$subtype] ?? $this->classmap[$type]['*'] ?? $this->defaultClass;
        if (empty($class)) {
            throw new InvalidConfigException("No class for type '$type'");
        }

        return $class;
    }
}
