<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\sale;

use hiqdev\billing\mrdp\Sale\SaleRepository;
use hiqdev\php\billing\sale\Sale;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class SaleRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(SaleRepository::class, $this->getRepo());
    }

    protected function getRepo(): SaleRepository
    {
        return $this->getRepository(Sale::class);
    }
}
