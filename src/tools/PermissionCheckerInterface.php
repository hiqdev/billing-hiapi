<?php

declare(strict_types=1);

namespace hiqdev\billing\hiapi\tools;

use hiqdev\php\billing\customer\CustomerInterface;

interface PermissionCheckerInterface
{
    public function checkAccess($clientId, string $permission): bool;

    public function ensureCustomerCan(CustomerInterface $customer, $permission): void;

    public function getRoles($clientId): array;
}
