<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\billing\hiapi\plan\GroupingPlan;
use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\QuantityInterface;

/**
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Generalizer extends \hiqdev\php\billing\charge\Generalizer
{
    /**
     * @var TypeSemantics
     */
    private $typeSemantics;

    public function __construct(TypeSemantics $typeSemantics)
    {
        $this->typeSemantics = $typeSemantics;
    }

    public function generalizeType(ChargeInterface $charge): TypeInterface
    {
        if ($this->typeSemantics->isDeposit($charge->getType())) {
            return $charge->getType();
        }

        if ($charge->getParent() !== null) {
            $chargeType = $charge->getParent()->getPrice()->getType();
        } else {
            $chargeType = $charge->getPrice()->getType();
        }

        if ($this->typeSemantics->isMonthly($chargeType)) {
            return $this->typeSemantics->createMonthlyType();
        }

        return $chargeType;
    }

    public function generalizeQuantity(ChargeInterface $charge): QuantityInterface
    {
        $action = $charge->getAction();

        if ($action->getSale() !== null && $this->typeSemantics->isMonthly($action->getType())) {
            $actionMonth = $action->getTime()->modify('first day of this month 00:00');
            $saleMonth = $action->getSale()->getTime()->modify('first day of this month 00:00');

            if ($saleMonth > $actionMonth) {
                $amount = 0;
            } elseif ($actionMonth > $saleMonth) {
                $amount = 1;
            } else {
                $saleDay = $action->getSale()->getTime()->format('d');
                $daysInMonth = $action->getSale()->getTime()->format('t');
                $amount = 1 - (($saleDay - 1) / $daysInMonth);
            }

            return Quantity::create('days', $amount);
        }

        return parent::generalizeQuantity($charge);
    }

    public function generalizeTarget(ChargeInterface $charge): TargetInterface
    {
        $plan = $charge->getPrice()->getPlan();
        if ($plan instanceof GroupingPlan) {
            return $plan->convertToTarget();
        }

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

    public function specializeTarget(TargetInterface $first, TargetInterface $other): TargetInterface
    {
        return $this->isMoreGeneral($first, $other) || !$first->hasId() ? $other : $first;
    }

    public function isMoreGeneral(TargetInterface $first, TargetInterface $other)
    {
        $i = 0;
        $order = [
            'domain' => ++$i,
            'zone' => ++$i,
            'certificate' => ++$i,
            'type' => ++$i,
            'part' => ++$i,
            'server' => ++$i,
            'device' => $i,
            'tariff' => ++$i,
            'ref' => ++$i,
            '' => ++$i,
        ];

        $lhs = $order[(string) $first->getType()] ?? 0;
        $rhs = $order[(string) $other->getType()] ?? 0;

        return $lhs > $rhs;
    }
}
