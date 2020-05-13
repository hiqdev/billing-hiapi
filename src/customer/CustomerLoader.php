<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\customer;

use hiqdev\php\billing\customer\Customer;
use League\Tactician\Middleware;
use yii\web\User;

class CustomerLoader implements Middleware
{
    private $repo;

    private $user;

    public function __construct(User $user, CustomerRepository $repo)
    {
        $this->user = $user;
        $this->repo = $repo;
    }

    public function execute($command, callable $next)
    {
        if (empty($command->customer)) {
            $command->customer = $this->findCustomer($command);
        }

        return $next($command);
    }

    private function findCustomer($command): Customer
    {
        $res = $this->findCustomerByCommand($command);
        if (empty($res)) {
            return $this->getCurrentCustomer();
        }

        return $res;
    }

    private function findCustomerByCommand($command): ?Customer
    {
        if (!empty($command->customer_id)) {
            return $this->repo->findById($command->customer_id);
        }
        if (!empty($command->customer_username)) {
            return $this->repo->findByUsername($command->customer_username);
        }

        return null;
    }

    private function getCurrentCustomer(): Customer
    {
        $identity = $this->user->getIdentity();

        return new Customer($identity->id, $identity->username ?: $identity->email);
    }
}
