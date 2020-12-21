<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\type;

use hiqdev\billing\mrdp\Type\TypeRepository;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\type\TypeInterface;
use League\Tactician\Middleware;
use OutOfRangeException;

class TypeLoader implements Middleware
{
    public string $typePrefix = '';

    private TypeRepository $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->type) && !empty($command->type_name)) {
            $command->type = $this->findPrefixed($this->typePrefix, $command->type_name);
        }

        return $next($command);
    }

    public function findPrefixed(string $prefix, string $typeName): ?TypeInterface
    {
        $type = $this->typeRepository->findOne(
            (new Specification())->where(['fullName' => "{$prefix},{$typeName}"])
        );
        if ($type === false) {
            throw new OutOfRangeException(sprintf('Type "%s" does not exist', $typeName));
        }

        return $type;
    }
}
