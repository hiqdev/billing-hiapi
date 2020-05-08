<?php

namespace hiqdev\billing\hiapi\bill\Search;

use hiapi\commands\SearchCommand;
use hiqdev\billing\hiapi\bill\BillSpecification;
use hiqdev\php\billing\bill\Bill;


/**
 * Class BillSearchCommand
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @method BillSpecification getSpecification()
 */
class BillSearchCommand extends SearchCommand
{
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->specificationClassName = BillSpecification::class;
    }

    public function getEntityClass(): string
    {
        return Bill::class;
    }
}
