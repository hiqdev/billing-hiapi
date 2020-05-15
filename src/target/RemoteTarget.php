<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\target\Target;

/**
 * Class RemoteTarget.
 */
class RemoteTarget extends Target
{
    /**
     * @var CustomerInterface
     */
    public $customer;

    /**
     * @var string
     */
    public $remoteid;

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function getRemoteId(): ?string
    {
        return $this->remoteid;
    }
}
