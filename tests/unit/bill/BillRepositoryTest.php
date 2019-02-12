<?php

namespace hiqdev\billing\hiapi\tests\unit\bill;

use hiqdev\php\billing\bill\Bill;
use hiqdev\billing\hiapi\bill\BillRepository;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class BillRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(BillRepository::class, $this->getRepo());
    }

    protected function getRepo(): BillRepository
    {
        return $this->getRepository(Bill::class);
    }
}
