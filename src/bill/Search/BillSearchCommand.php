<?php

namespace hiqdev\billing\hiapi\bill\Search;

use hiapi\commands\Search\Filter\Type\IntegerFilter;
use hiapi\commands\SearchCommand;
use hiqdev\billing\hiapi\models\Bill as BillModel;
use hiapi\commands\Search\Filter\Type\GenericFilter as Filter;
use hiqdev\billing\hiapi\models\Customer;
use hiqdev\billing\hiapi\models\Money;
use hiqdev\billing\hiapi\models\Plan;
use hiqdev\billing\hiapi\models\Quantity;
use hiqdev\billing\hiapi\models\Target;
use hiqdev\billing\hiapi\models\Type;
use hiqdev\yii\DataMapper\query\attributes\DateTimeAttribute;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\validators\WhereValidator;

class BillSearchCommand extends SearchCommand
{
    public function getEntityClass(): string
    {
        return Bill::class;
    }

    public function filters()
    {
        $filters = new FiltersCollection();
        $filters->addMultiple(
            // Probably good. Except too many boilerplate classes and imperative code style
            new IntegerFilter('id'),
            // Also may be good, but harder to extend
            Filter::integer('seller_id'),
            // Would not go this way
            ['client_id', Filter::INTEGER],

//            new IntegerFilter('object_id'),
//            new RefFilter('type'),
//            new MoneyFilter('sum'),
//            new DateTimeFilter('time')
        );
    }
}
