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
use hiqdev\billing\hiapi\sale\SaleRepository;

class SaleBulkSearchAction
{
    /**
     * @var SaleRepository
     */
    private $repo;

    public function __construct(SaleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(SaleSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
