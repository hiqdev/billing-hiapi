<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tools;

use hiqdev\billing\hiapi\charge\Generalizer;
use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\charge\GeneralizerInterface;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\QuantityInterface;
use hiqdev\php\units\Unit;

/**
 * Normally, when monthly charges are put in the single bill, the bill quantity
 * increases accordingly. But it's not the right scenario for monthly bills: all
 * the charges are for the month quantity, but the whole bill is for one month as well.
 *
 * Class MonthlyBillQuantityFixer is applicable only for monthly bills and
 * adjusts the quantity of monthly bills according to the number of days in month.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class MonthlyBillQuantityFixer
{
    /**
     * @var Generalizer
     */
    private $generalizer;

    /**
     * @var TypeSemantics
     */
    private $typeSemantics;

    public function __construct(
        GeneralizerInterface $generalizer,
        TypeSemantics $typeSemantics
    ) {
        $this->generalizer = $generalizer;
        $this->typeSemantics = $typeSemantics;
    }

    /**
     * @param BillInterface $bill
     */
    public function __invoke($bill): void
    {
        if ($this->typeSemantics->isMonthly($bill->getType())) {
            $bill->setQuantity($this->calculateMonthlyQuantity($bill));
        }
    }

    private function calculateMonthlyQuantity(BillInterface $bill): QuantityInterface
    {
        $res = null;
        foreach ($bill->getCharges() as $charge) {
            $amount = $this->generalizer->generalizeQuantity($charge);
            if (!$amount->getUnit()->isConvertible(Unit::items())) {
                continue;
            }
            if ($res === null || $amount->compare($res)>0) {
                $res = $amount;
            }
        }

        return $res ?? Quantity::create('items', 1);
    }
}
