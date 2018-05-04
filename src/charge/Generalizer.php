<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\php\billing\target\TargetInterface;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Generalizer extends \hiqdev\php\billing\charge\Generalizer
{
    public function generalizeTarget(ChargeInterface $charge)
    {
        return $this->moreGeneral($charge->getAction()->getTarget(), $charge->getPrice()->getTarget());

        /* Sorry, to be removed later, older variants
         * 1:
            if (in_array($charge->getTarget()->getType(), ['certificate', 'domain'], TRUE)) {
                $priceTarget = $charge->getPrice()->getTarget();
                if ($priceTarget->getId()) {
                    return $priceTarget;
                }
            }
            return parent::generalizeTarget($charge);
         * 2:
            return $priceTarget->getId() ? $priceTarget : new Target($charge->getSale()->getPlan()->getId(), 'plan');
         */
    }

    public function moreGeneral(TargetInterface $first, TargetInterface $other)
    {
        return $this->isMoreGeneral($first, $other) || !$other->hasId() ? $first : $other;
    }

    public function lessGeneral(TargetInterface $first, TargetInterface $other)
    {
        return $this->isMoreGeneral($first, $other) || !$first->hasId() ? $other : $first;
    }

    public function isMoreGeneral(TargetInterface $first, TargetInterface $other)
    {
        $i = 0;
        $order = [
            'domain'        => ++$i,
            'zone'          => ++$i,
            'certificate'   => ++$i,
            'type'          => ++$i,
            'part'          => ++$i,
            'server'        => ++$i,
            'device'        =>   $i,
            'tariff'        => ++$i,
            'ref'           => ++$i,
            ''              => ++$i,
        ];

        $lhs = $order[(string) $first->getType()];
        $rhs = $order[(string) $other->getType()];

        return $lhs > $rhs;
    }
}
