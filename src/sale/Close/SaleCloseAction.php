<?php

namespace hiqdev\billing\hiapi\sale\Close;

use hiqdev\billing\hiapi\sale\SaleRepository;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\sale\Sale;
use hiapi\exceptions\domain\RequiredInputException;

class SaleCloseAction
{
    /**
     * @var SaleRepository
     */
    private $repo;

    public function __construct(SaleRepository $repo)
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
