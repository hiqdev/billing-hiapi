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

namespace hiqdev\billing\hiapi\sale\Search;

use hiapi\Core\Auth\AuthRule;
use hiapi\endpoints\Module\InOutControl\VO\Count;
use hiqdev\billing\mrdp\Sale\HistoryAndFutureSaleRepository;
use hiqdev\php\billing\sale\SaleRepositoryInterface;

class CountAction
{
    private SaleRepositoryInterface $repo;
    private HistoryAndFutureSaleRepository $historyRepo;

    public function __construct(SaleRepositoryInterface $repo, HistoryAndFutureSaleRepository $historyRepo)
    {
        $this->repo = $repo;
        $this->historyRepo = $historyRepo;
    }

    public function __invoke(Command $command): Count
    {
        $specification = AuthRule::currentUser()->applyToSpecification($command->getSpecification());

        if (in_array('history', $command->include, true)) {
            $count = $this->historyRepo->count($specification);
        } else {
            $count = $this->repo->count($specification);
        }

        return Count::is($count);
    }
}
