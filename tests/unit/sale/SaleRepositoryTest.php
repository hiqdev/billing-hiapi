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

use hiqdev\php\billing\sale\Sale;
use hiqdev\php\billing\sale\SaleRepositoryInterface;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class SaleRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(SaleRepositoryInterface::class, $this->getRepo());
    }

    protected function getRepo(): SaleRepositoryInterface
    {
        return $this->getRepository(Sale::class);
    }
}
