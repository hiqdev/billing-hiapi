<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiqdev\billing\hiapi\models\Action;

abstract class AggregatedConsumptionsQuery extends \hiqdev\yii\DataMapper\query\Query
{
    /**
     * @var string
     */
    protected $modelClass = Action::class;
}
