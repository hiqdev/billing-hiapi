<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Close;

use hiapi\exceptions\domain\RequiredInputException;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\sale\Sale;

class SaleCloseAction
{
    /**
     * @var SaleRepositoryInterface
     */
    private $repo;

    public function __construct(SaleRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(SaleCloseCommand $command): Sale
    {
        $this->checkRequiredInput($command);
        $plan = new Plan($command->plan_id, null);
        $sale = new Sale(null, $command->target, $command->customer, $plan, $command->time);
        $this->repo->delete($sale);

        return $sale;
    }

    protected function checkRequiredInput(SaleCloseCommand $command)
    {
        if (empty($command->customer)) {
            throw new RequiredInputException('customer');
        }
        if (empty($command->target)) {
            throw new RequiredInputException('target');
        }
    }
}
