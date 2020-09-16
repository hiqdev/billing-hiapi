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

use hiqdev\billing\hiapi\target\certificate\CertificateTarget;
use hiqdev\billing\hiapi\target\client\ClientTarget;
use hiqdev\billing\hiapi\target\device\PixCdnTarget;
use hiqdev\billing\hiapi\target\device\ServerTarget;
use hiqdev\billing\hiapi\target\device\SwitchTarget;
use hiqdev\billing\hiapi\target\domain\DomainTarget;
use hiqdev\billing\hiapi\target\domain\DomainZoneTarget;
use hiqdev\billing\hiapi\target\domain\RegdomainTarget;
use hiqdev\billing\hiapi\target\modelGroup\ModelGroupTarget;
use hiqdev\billing\hiapi\target\part\PartTarget;
use hiqdev\billing\hiapi\target\ref\RefTarget;
use hiqdev\billing\hiapi\target\tariff\CertificateTariffTarget;
use hiqdev\billing\hiapi\target\tariff\DomainTariffTarget;
use hiqdev\billing\hiapi\target\tariff\PcdnTariffTarget;
use hiqdev\billing\hiapi\target\tariff\PrivateCloudTariffTarget;
use hiqdev\billing\hiapi\target\tariff\ReferralTariffTarget;
use hiqdev\billing\hiapi\target\tariff\ServerTariffTarget;
use hiqdev\billing\hiapi\target\tariff\SnapshotTariffTarget;
use hiqdev\billing\hiapi\target\tariff\StorageTariffTarget;
use hiqdev\billing\hiapi\target\tariff\SwitchTariffTarget;
use hiqdev\billing\hiapi\target\tariff\TariffTarget;
use hiqdev\billing\hiapi\target\tariff\TemplateTariffTarget;
use hiqdev\billing\hiapi\target\tariff\VcdnTariffTarget;
use hiqdev\billing\hiapi\target\tariff\VolumeTariffTarget;
use hiqdev\billing\hiapi\target\tariff\VpsTariffTarget;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetCreationDto;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\units\exceptions\InvalidConfigException;
use hiqdev\billing\mrdp\Target\Feature\WhoisProtect;

/**
 * TODO move to `billling-mrdp` together with targets
 */
class TargetFactory implements TargetFactoryInterface
{
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
        $map = [
            'device' => [
                'cdn' => device\VideoCdnTarget::class,
                'cloudservice' => device\VideoCdnTarget::class,
                'cdnpix' => PixCdnTarget::class,
                'net' => SwitchTarget::class,
                'rack' => SwitchTarget::class,
                'cable_organizer' => SwitchTarget::class,
                'console' => SwitchTarget::class,
                'ipmi' => SwitchTarget::class,
                'pdu' => SwitchTarget::class,
                '*' => ServerTarget::class,
            ],
            'serverConfig' => [
                '*' => Target::class,
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
                'vcdn' => VcdnTariffTarget::class,
                'pcdn' => PcdnTariffTarget::class,
                'server' => ServerTariffTarget::class,
                'template' => TemplateTariffTarget::class,
                'certificate' => CertificateTariffTarget::class,
                'domain' => DomainTariffTarget::class,
                'switch' => SwitchTariffTarget::class,
                'referral' => ReferralTariffTarget::class,
                'vps' => VpsTariffTarget::class,
                'snapshot' => SnapshotTariffTarget::class,
                'volume' => VolumeTariffTarget::class,
                'storage' => StorageTariffTarget::class,
                'private_cloud_backup' => PrivateCloudTariffTarget::class,
                'private_cloud' => PrivateCloudTariffTarget::class,
                '*' => TariffTarget::class,
            ],
            'client' => [
                '*' => ClientTarget::class,
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
            'feature' => [
                '*' => Target::class,
            ],
            'whois_protect_purchase' => [
                '*' => WhoisProtect::class,
            ],
            'domain' => [
                '*' => DomainTarget::class,
            ],
            'regdomain' => [
                '*' => RegdomainTarget::class,
            ],
            'zone' => [
                '*' => DomainZoneTarget::class,
            ],
            'anycastcdn' => [
                '*' => Remote\AnycastCdnTarget::class,
            ],
            'videocdn' => [
                '*' => Remote\VideoCdnTarget::class,
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
