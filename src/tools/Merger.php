<?php

namespace hiqdev\billing\hiapi\tools;

use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\tools\MergerInterface;

/**
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Merger implements MergerInterface
{
    /**
     * @var MonthlyBillQuantityFixer
     */
    private $monthlyBillQuantityFixer;
    /**
     * @var MergerInterface
     */
    private $defaultMerger;

    public function __construct(MergerInterface $defaultMerger, MonthlyBillQuantityFixer $monthlyBillQuantityFixer)
    {
        $this->defaultMerger = $defaultMerger;
        $this->monthlyBillQuantityFixer = $monthlyBillQuantityFixer;
    }

    /**
     * {@inheritdoc}
     */
    public function mergeBills(array $bills): array
    {
        $bills = $this->defaultMerger->mergeBills($bills);

        foreach ($bills as $bill) {
            $this->monthlyBillQuantityFixer->__invoke($bill);
        }

        return $bills;
    }

    public function mergeBill(BillInterface $first, BillInterface $other): BillInterface
    {
        return $this->defaultMerger->mergeBill($first, $other);
    }
}
