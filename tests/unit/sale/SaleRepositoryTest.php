<?php

namespace hiqdev\billing\hiapi\tests\unit\sale;

use hiqdev\php\billing\sale\Sale;
use hiqdev\billing\hiapi\sale\SaleRepository;
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
