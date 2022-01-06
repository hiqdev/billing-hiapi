<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\Create;

use hiapi\exceptions\HiapiException;
use hiqdev\billing\hiapi\target\RemoteTargetCreationDto;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\target\TargetWasCreated;
use League\Event\EmitterInterface;

final class Action
{
    private TargetFactoryInterface $targetFactory;
    private TargetRepositoryInterface $targetRepo;
    private EmitterInterface $emitter;

    public function __construct(
        TargetRepositoryInterface $targetRepo,
        TargetFactoryInterface $targetFactory,
        EmitterInterface $emitter
    ) {
        $this->targetRepo = $targetRepo;
        $this->targetFactory = $targetFactory;
        $this->emitter = $emitter;
    }

    public function __invoke(Command $command): Target
    {
        $this->ensureDoesNotExist($command->type, $command->name);
        $target = $this->createTarget($command);

        return $target;
    }

    private function ensureDoesNotExist(string $type, string $name): void
    {
        $spec = (new Specification)->where([
            'type' => $type,
            'name' => $name,
        ]);

        $target = $this->targetRepo->findOne($spec);
        if ($target !== false) {
            throw new HiapiException(sprintf('Target "%s" for type "%s" already exists with ID %s',
                $name, $type, $target->getId()
            ));
        }
    }

    private function createTarget(Command $command): Target
    {
        $dto = new RemoteTargetCreationDto();
        $dto->name = $command->name;
        $dto->type = $command->type;
        $dto->customer = $command->customer;
        $dto->remoteid = $command->remoteid;

        $target = $this->targetFactory->create($dto);
        $this->targetRepo->save($target);
        $this->emitter->emit(TargetWasCreated::occurred($target));

        return $target;
    }
}
