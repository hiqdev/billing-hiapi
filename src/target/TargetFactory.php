<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\billing\hiapi\target\certificate\CertificateTarget;
use hiqdev\billing\hiapi\target\device\ServerTarget;
use hiqdev\billing\hiapi\target\modelGroup\ModelGroupTarget;
use hiqdev\billing\hiapi\target\part\PartTarget;
use hiqdev\billing\hiapi\target\ref\RefTarget;
use hiqdev\billing\hiapi\target\tariff\ServerTariffTarget;
use hiqdev\billing\hiapi\target\tariff\TariffTarget;
use hiqdev\billing\hiapi\target\tariff\TemplateTariffTarget;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetCreationDto;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\units\exceptions\InvalidConfigException;

class TargetFactory implements TargetFactoryInterface
{
    public function getEntityClassName(): string
    {
        return Target::class;
    }

    /**
     * @return Target|null
     */
    public function create(TargetCreationDto $dto): ?Target
    {
        if (!isset($dto->type)) {
            $class = Target::class;
        } else {
            $class = $this->getClassForType($dto->type);
            $dto->type = $this->shortenType($dto->type);
        }

        return new $class($dto->id, $dto->type, $dto->name);
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
        $map = [
            'device' => [
                '*' => ServerTarget::class,
            ],
            'part' => [
                '*' => PartTarget::class,
            ],
            'server' => [
                '*' => ServerTarget::class,
            ],
            'certificate' => [
                '*' => CertificateTarget::class,
            ],
            'ref' => [
                '*' => RefTarget::class,
            ],
            'tariff' => [
                'server' => ServerTariffTarget::class,
                'template' => TemplateTariffTarget::class,
                '*' => TariffTarget::class,
            ],
            'client' => [
                '*' => ServerTarget::class,
            ],
            'account' => [
                '*' => ServerTarget::class,
            ],
            'model_group' => [
                '*' => ModelGroupTarget::class,
            ],
            'type' => [
                '*' => Target::class,
            ],
            '-1' => [
                '*' => Target::class,
            ],
        ];

        [$type, $subtype] = $this->parseType($type);
        $class = $map[$type][$subtype] ?? $map[$type]['*'] ?? null;
        if ($class === null) {
            throw new InvalidConfigException("No class for type '$type'");
        }

        return $class;
    }
}
