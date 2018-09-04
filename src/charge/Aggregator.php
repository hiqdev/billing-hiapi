<?php

namespace hiqdev\billing\hiapi\charge;

use hiqdev\billing\hiapi\type\TypeSemantics;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\bill\BillRepositoryInterface;
use hiqdev\php\billing\charge\GeneralizerInterface;
use hiqdev\php\units\QuantityInterface;

/**
 * Class Aggregator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Aggregator extends \hiqdev\php\billing\charge\Aggregator
{
    /**
     * @var TypeSemantics
     */
    private $typeSemantics;

    public function __construct(
        GeneralizerInterface $generalizer,
        BillRepositoryInterface $billRepository,
        TypeSemantics $typeSemantics
    ) {
        parent::__construct($generalizer, $billRepository);

        $this->typeSemantics = $typeSemantics;
    }

    protected function aggregateQuantity(BillInterface $first, BillInterface $other): QuantityInterface
    {
        $billType = $first->getType();

        if ($this->typeSemantics->isMonthly($billType)) {
            return $first->getQuantity();
        }

        return $first->getQuantity()->add($other->getQuantity());
    }
}
