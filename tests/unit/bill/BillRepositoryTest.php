<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\bill;

use hiqdev\php\billing\bill\Bill;
use hiqdev\php\billing\bill\BillRepositoryInterface;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class BillRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(BillRepositoryInterface::class, $this->getRepo());
    }

    protected function getRepo(): BillRepositoryInterface
    {
        return $this->getRepository(Bill::class);
    }
}
