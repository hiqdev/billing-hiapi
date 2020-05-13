<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\method\Search;

use Doctrine\Common\Collections\ArrayCollection;
use hiqdev\billing\hiapi\method\MethodRepository;

class MethodBulkSearchAction
{
    /**
     * @var MethodRepository
     */
    private $repo;

    public function __construct(MethodRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(MethodSearchCommand $command): ArrayCollection
    {
        $res = $this->repo->findAll($command->getSpecification());

        return new ArrayCollection($res);
    }
}
