<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Cancel;

use DateTimeImmutable;
use DomainException;
use hiqdev\billing\hiapi\feature\FeatureDto;
use hiqdev\billing\hiapi\feature\FeatureFactoryInterface;
use hiqdev\billing\hiapi\feature\FeatureInterface;
use hiqdev\billing\hiapi\feature\FeatureRepositoryInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;

final class Action
{
    private FeatureRepositoryInterface $featureRepository;
    private FeatureFactoryInterface $featureFactory;
    private TargetRepositoryInterface $targetRepository;

    public function __construct(
        FeatureFactoryInterface $featureFactory,
        FeatureRepositoryInterface $featureRepository,
        TargetRepositoryInterface $targetRepository
    ) {
        $this->featureRepository = $featureRepository;
        $this->featureFactory = $featureFactory;
        $this->targetRepository = $targetRepository;
    }

    public function __invoke(Command $command): FeatureInterface
    {
        $dto = new FeatureDto();
        $dto->type = $command->type;
        $dto->target = $command->target;

        $feature = $this->findAndEnsureCanBeDisabled($dto);
        $feature->setExpires(new DateTimeImmutable());

        $this->featureRepository->save($feature);

        return $feature;
    }

    private function findAndEnsureCanBeDisabled(FeatureDto $dto): FeatureInterface
    {
        $feature = $this->featureFactory->create($dto);
        $existingFeature = $this->featureRepository->findUnique($feature);
        if ($existingFeature === null) {
            throw new DomainException(sprintf(
                'No active feature of type "%s" found. Nothing can be disabled',
                $feature->type()->getName(),
            ));
        }

        if ($existingFeature->expires() !== null
            && $existingFeature->expires() < new DateTimeImmutable()
        ) {
            throw new DomainException(sprintf(
                'No active feature of type "%s" found. Nothing can be disabled',
                $feature->type()->getName(),
            ));
        }

        return $existingFeature;
    }
}
