<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Purchase;

use DateTimeImmutable;
use DomainException;
use hiapi\Core\Auth\AuthRule;
use hiqdev\billing\hiapi\feature\FeatureDto;
use hiqdev\billing\hiapi\feature\FeatureServiceInterface;
use hiqdev\billing\hiapi\feature\FeatureFactoryInterface;
use hiqdev\billing\hiapi\feature\FeatureInterface;
use hiqdev\billing\hiapi\feature\FeatureRepositoryInterface;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;

final class Action
{
    private FeatureRepositoryInterface $featureRepository;
    private FeatureFactoryInterface $featureFactory;
    private TargetRepositoryInterface $targetRepository;
    private FeatureServiceInterface $featureService;

    public function __construct(
        FeatureFactoryInterface $featureFactory,
        FeatureRepositoryInterface $featureRepository,
        TargetRepositoryInterface $targetRepository,
        FeatureServiceInterface $featureService
    ) {
        $this->featureRepository = $featureRepository;
        $this->featureFactory = $featureFactory;
        $this->targetRepository = $targetRepository;
        $this->featureService = $featureService;
    }

    public function __invoke(Command $command): FeatureInterface
    {
        $dto = new FeatureDto();
        $dto->type = $command->type;
        $dto->target = $command->target;
        $dto->starts = new DateTimeImmutable();
        $dto->amount = $command->amount;

        $feature = $this->featureFactory->create($dto);
        $this->calculateExpirationTime($feature, $command->amount);
        $this->ensureCanBeEnabled($feature);
        $this->featureRepository->save($feature);
        $this->featureService->activate($feature);

        return $feature;
    }

    private function calculateExpirationTime(FeatureInterface $feature, $amount): void
    {
        $feature->setExpires(
            $this->featureService->calculateExpiration($feature, $amount)
        );
    }

    private function ensureCanBeEnabled(FeatureInterface $feature): void
    {
        $existingFeature = $this->featureRepository->findUnique($feature);
        if ($existingFeature === null) {
            return;
        }

        if ($existingFeature->expires() === null && $feature->expires() !== null) {
            // Unlimited feature becomes limited
            return;
        }

        if ($feature->expires() !== null) {
            if ($existingFeature->expires() > $feature->expires()) {
                throw new DomainException(sprintf(
                    "Feature '%s' is already active for the passed object '%s' and lasts longer. "
                    . 'If you want to shorten existing feature, disable it and create a new one',
                    $feature->type()->getName(),
                    $feature->target()->getId(),
                ));
            }

            if ($existingFeature->expires() <= $feature->expires()) {
                throw new DomainException(sprintf(
                    "Feature '%s' is already active for the passed object '%s'. "
                    . 'If you want to renew existing feature, use a "renew" API endpoint',
                    $feature->type()->getName(),
                    $feature->target()->getId(),
                ));
            }
        }

        throw new DomainException(sprintf(
            "Feature '%s' is already active for the passed object '%s'",
            $feature->type()->getName(),
            $feature->target()->getId(),
        ));
    }

    private function createTarget(Command $command): TargetInterface
    {
        $spec = (new Specification())->where(['id' => $command->object_id]);
        $target = $this->targetRepository->findOne(
            AuthRule::currentUser()->applyToSpecification($spec)
        );
        if ($target === false) {
            throw new DomainException(sprintf('Could not find object "%s"', $command->object_id));
        }

        return $target;
    }
}
