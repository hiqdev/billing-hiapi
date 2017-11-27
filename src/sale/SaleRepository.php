<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\sale;

use hiapi\db\CallExpression;
use hiapi\db\HstoreExpression;
use hiapi\components\EntityManagerInterface;
use hiapi\repositories\BaseRepository;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\sale\SaleFactoryInterface;
use hiqdev\php\billing\sale\SaleQuery;
use hiqdev\php\billing\sale\Sale;

class SaleRepository extends BaseRepository
{
    public $queryClass = SaleQuery::class;

    /**
     * @var SaleFactory
     */
    protected $factory;

    public function __construct(
        EntityManagerInterface $em,
        SaleFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->em = $em;
        $this->factory = $factory;
    }

    public function findId(SaleInterface $sale)
    {
        $hstore = new HstoreExpression(array_filter([
            'buyer'     => $sale->getCustomer()->getLogin(),
            'buyer_id'  => $sale->getCustomer()->getId(),
            'object_id' => $sale->getTarget()->getId(),
            'tariff_id' => $sale->getPlan()->getId(),
        ]));
        $call = new CallExpression('sale_id', [$hstore]);
        $command = $this->em->getConnection()->createSelect($call);

        return $command->scalar();
    }
}
