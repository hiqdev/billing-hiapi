<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiapi\Core\Auth\AuthRule;
use hiqdev\billing\mrdp\Sale\HistoryAndFutureSaleRepository;
use hiqdev\php\billing\sale\SaleRepositoryInterface;

class BulkAction
{
    /**
     * @var SaleRepositoryInterface
     */
    private $repo;
    /**
     * @var HistoryAndFutureSaleRepository
     */
    private HistoryAndFutureSaleRepository $historyRepo;

    public function __construct(SaleRepositoryInterface $repo, HistoryAndFutureSaleRepository $saleRepository)
    {
        $this->repo = $repo;
        $this->historyRepo = $saleRepository;
    }

    public function __invoke(Command $command): ArrayCollection
    {
        $specification = AuthRule::currentUser()->applyToSpecification($command->getSpecification());

        if (in_array('history', $command->include, true)) {
            $res = $this->historyRepo->findAll($specification);
        } else {
            $res = $this->repo->findAll($specification);
        }

        return new ArrayCollection($res);
    }
}
