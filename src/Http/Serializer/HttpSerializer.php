<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\Http\Serializer;

use Closure;
use yii\web\User;
use Laminas\Hydrator\ExtractionInterface;
use Laminas\Hydrator\HydratorInterface;

/**
 * Class HttpSerializer implements ExtractionInterface and should be used
 * to serialize objects for HTTP responses and hide some sensitive information
 * from the serialized objects.
 *
 * This is a trick to re-use existing Hydrators and do not implement them again
 * just for HTTP interaction.
 *
 * The HTTP formatter should call {@see extract()} method of this class,
 * then, this class calls the concrete Hydrator implementations.
 *
 * The underlying Hydrator implementation may depend on HttpSerializer and
 * use its {@see ensureBeforeCall()} method to check, if it was called in a context
 * of HTTP response preparation.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class HttpSerializer implements ExtractionInterface
{
    private User $user;
    private HydratorInterface $hydrator;

    public function __construct(User $user, HydratorInterface $hydrator)
    {
        $this->user = $user;
        $this->hydrator = $hydrator;
    }

    private bool $isRunning = false;

    public function extract(object $object): array
    {
        $wasRunning = $this->isRunning;
        $this->isRunning = true;
        try {
            return $this->hydrator->extract($object);
        } finally {
            $this->isRunning = $wasRunning;
        }
    }

    public function ensureBeforeCall($permissionOrClosure, Closure $closure, $fallback = null)
    {
        if (!$this->isRunning) {
            return $closure();
        }

        if (is_string($permissionOrClosure)) {
            if ($this->user->can($permissionOrClosure)) {
                return $closure();
            }
            return $fallback;
        }

        if ($permissionOrClosure instanceof Closure) {
            if ($permissionOrClosure($this->user)) {
                return $closure();
            }
            return $fallback;
        }

        return $fallback;
    }
}
