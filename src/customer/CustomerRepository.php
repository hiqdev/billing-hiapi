<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\customer;

use hiqdev\php\billing\customer\CustomerInterface;

class CustomerRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /** {@inheritdoc} */
    public $queryClass = CustomerQuery::class;

    public function findByUsername(string $username): CustomerInterface
    {
        $spec = $this->createSpecification()->where(['login' => $username]);

        return $this->findOne($spec);
    }

    public function save(CustomerInterface $customer)
    {
        throw new \Exception('not implemented ' . __METHOD__);
    }
}
