<?php

declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\ChangePlan;

use DateTimeImmutable;
use hiqdev\billing\mrdp\Sale\HistoryAndFutureSaleRepository;
use hiqdev\DataMapper\Query\Specification;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\target\TargetInterface;

class ActiveSaleFinder
{
    public function __construct(protected readonly HistoryAndFutureSaleRepository $saleRepository)
    {
    }

    public function __invoke(
        TargetInterface $target,
        CustomerInterface $customer,
        DateTimeImmutable $time
    ): ?SaleInterface
    {
        $spec = (new Specification())->where([
            'seller-id' => $customer->getSeller()?->getId(),
            'target-id' => $target->getId(),
        ]);
        /** @var SaleInterface|false $sale */
        $sale = $this->saleRepository->findOneAsOfDate($spec, $time);
        if ($sale === false) {
            return null;
        }

        return $sale;
    }
}
