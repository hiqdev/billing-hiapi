<?php

namespace hiqdev\billing\hiapi\target;

use hiqdev\billing\hiapi\target\device\ServerTarget;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetCreationDto;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\units\exceptions\InvalidConfigException;

class TargetFactory implements TargetFactoryInterface
{
    /**
     * @return Target|null
     */
    public function create(TargetCreationDto $dto): ?Target
    {
        if (!isset($dto->type)) {
            $class = Target::class;
        } else {
            $class = $this->getClassForType($dto);
        }

        return new $class($dto->id, $dto->type);
    }

    protected function getClassForType(TargetCreationDto $dto): string
    {
        $map = [
            'device' => [
                '*' => ServerTarget::class,
            ]
        ];

        $type = $dto->type;
        $subtype = '*';

        if (strpos($type, '.') !== false) {
            [$type, $subtype] = explode('.', $type, 2);
        }

        $class = $map[$type][$subtype] ?? $map[$type]['*'] ?? null;

        if ($class === null) {
            throw new InvalidConfigException('No class for type' . $dto->type);
        }

        return $class;
    }
}