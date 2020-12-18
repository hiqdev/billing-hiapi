<?php
declare(strict_types=1);
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action\Calculate;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\php\billing\order\BillingInterface;

final class Action
{
    private BillingInterface $billing;

    public function __construct(BillingInterface $billing)
    {
        $this->billing = $billing;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $actions = $command->getActions();
        $charges = $this->billing->calculateCharges($actions);
        return new ArrayCollection($charges);
    }
}
