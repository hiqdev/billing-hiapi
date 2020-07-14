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
use hiqdev\php\billing\customer\CustomerRepositoryInterface;
use League\Tactician\Middleware;
use yii\web\User;
use hiqdev\DataMapper\Query\Specification;
use hiapi\Core\Auth\AuthRule;

class CustomerLoader implements Middleware
{
    private $repo;

    private $user;

    public function __construct(User $user, CustomerRepositoryInterface $repo)
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
            $where = ['id' => $command->customer_id];
        } elseif (!empty($command->customer_username)) {
            $where = ['login' => $command->customer_username];
        } else {
            return null;
        }
        $spec = AuthRule::currentUser()->applyToSpecification(
            (new Specification)->where($where)
        );

        return $this->repo->findOne($spec) ?: null;
    }

    private function getCurrentCustomer(): Customer
    {
        $identity = $this->user->getIdentity();
        $seller = new Customer($identity->seller_id, $identity->seller);

        return new Customer($identity->id, $identity->username ?: $identity->email, $seller);
    }
}
