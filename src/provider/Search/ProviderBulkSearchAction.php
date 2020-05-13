<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\provider\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\provider\ProviderRepository;

class ProviderBulkSearchAction
{
    /**
     * @var ProviderRepository
     */
    private $repo;

    public function __construct(ProviderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(ProviderSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
