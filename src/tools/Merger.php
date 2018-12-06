<?php

namespace hiqdev\billing\hiapi\tools;

use hiqdev\billing\hiapi\charge\Generalizer;
use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\charge\GeneralizerInterface;
use hiqdev\php\units\Unit;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\QuantityInterface;

/**
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Merger extends \hiqdev\php\billing\tools\Merger
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
     * {@inheritdoc}
     */
    public function mergeBills(array $bills): array
    {
        $bills = parent::mergeBills($bills);

        return $this->fixBills($bills);
    }

    /**
     * @param BillInterface[] $bills
     * @return BillInterface[]
     */
    protected function fixBills(array $bills): array
    {
        foreach ($bills as $bill) {
            if ($this->typeSemantics->isMonthly($bill->getType())) {
                $bill->setQuantity($this->calculateMonthlyQuantity($bill));
            }
        }

        return $bills;
    }

    protected function calculateMonthlyQuantity(BillInterface $bill): QuantityInterface
    {
        $res = null;
        foreach ($bill->getCharges() as $charge) {
            $amount = $this->generalizer->generalizeQuantity($charge);
            if (!$amount->getUnit()->isConvertible(Unit::days())) {
                continue;
            }
            if ($res === null || $amount->compare($res)>0) {
                $res = $amount;
            }
        }
        if ($res === null) {
            return Quantity::create('days', 1);
        }

        return $res;
    }
}
