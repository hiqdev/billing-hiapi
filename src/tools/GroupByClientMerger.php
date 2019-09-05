<?php

namespace hiqdev\billing\hiapi\tools;

use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\tools\MergerInterface;

/**
 * Class GroupByClientMerger merges bills by the buyer.
 *
 * Could be used to get totals by client in a list of bills.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class GroupByClientMerger implements MergerInterface
{
    private const CUSTOMER_UNKNOWN = 'guest';

    /**
     * @var MergerInterface
     */
    private $defaultMerger;
    /**
     * @var MonthlyBillQuantityFixer
     */
    private $monthlyBillQuantityFixer;

    public function __construct(MergerInterface $defaultMergingStrategy, MonthlyBillQuantityFixer $monthlyBillQuantityFixer)
    {
        $this->defaultMerger = $defaultMergingStrategy;
        $this->monthlyBillQuantityFixer = $monthlyBillQuantityFixer;
    }

    /**
     * Merges array of bills.
     * @param BillInterface[] $bills
     * @return BillInterface[]
     */
    public function mergeBills(array $bills): array
    {
        $res = [];
        foreach ($bills as $bill) {
            $buyer = $bill->getCustomer()->getUniqueId() ?? self::CUSTOMER_UNKNOWN;

            if (empty($res[$buyer])) {
                $res[$buyer] = $bill;
            } else {
                $res[$buyer] = $this->mergeBill($res[$buyer], $bill);
            }
        }

        return $res;
    }

    /**
     * Merges two bills in one.
     * @param BillInterface $first
     * @param BillInterface $other
     * @return BillInterface
     */
    public function mergeBill(BillInterface $first, BillInterface $other): BillInterface
    {
        $bill = $this->defaultMerger->mergeBill($first, $other);
        $this->monthlyBillQuantityFixer->__invoke($bill);

        return $bill;
    }
}
