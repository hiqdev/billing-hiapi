<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale\Create;

use hiapi\exceptions\domain\RequiredInputException;
use hiqdev\billing\hiapi\sale\SaleRepository;
use hiqdev\php\billing\sale\Sale;

class SaleCreateAction
{
    /**
     * @var SaleRepository
     */
    private $repo;

    public function __construct(SaleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(SaleCreateCommand $command): Sale
    {
        $this->checkRequiredInput($command);
        $sale = new Sale(null, $command->target, $command->customer, $command->plan, $command->time);
        $this->repo->save($sale);

        return $sale;
    }

    protected function checkRequiredInput(SaleCreateCommand $command)
    {
        if (empty($command->customer)) {
            throw new RequiredInputException('customer');
        }
        if (empty($command->target)) {
            throw new RequiredInputException('target');
        }
        if (empty($command->plan)) {
            throw new RequiredInputException('plan');
        }
    }
}
