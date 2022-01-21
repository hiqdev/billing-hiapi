<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\Http\Serializer;

class HttpSerializer
{
    private \yii\web\User $user;
    private \Zend\Hydrator\HydratorInterface $hydrator;

    public function __construct(\yii\web\User $user, \Zend\Hydrator\HydratorInterface $hydrator)
    {
        $this->user = $user;
        $this->hydrator = $hydrator;
    }

    private bool $isRunning = false;

    public function extract(object $object)
    {
        $wasRunning = $this->isRunning;
        $this->isRunning = true;
        try {
            return $this->hydrator->extract($object);
        } finally {
            $this->isRunning = $wasRunning;
        }
    }

    public function ensurePermissionBeforeCall($permissionOrClosure, \Closure $closure, $fallback = null)
    {
        if (!$this->isRunning) {
            return $closure();
        }

        if (is_string($permissionOrClosure) && $this->user->can($permissionOrClosure)) {
            return $closure();
        }

        if ($permissionOrClosure instanceof \Closure && $permissionOrClosure($this->user)) {
            return $closure();
        }

        return $fallback;
    }

    public function ensureBeforeCall(string $permission, \Closure $closure, $fallback = null)
    {
        if (!$this->isRunning) {
            return $closure();
        }

        if ($this->user->can($permission)) {
            return $closure();
        }

        return $fallback;
    }
}
