<?php

namespace hiqdev\billing\hiapi\tests\unit\plan;

use hiqdev\php\billing\plan\Plan;
use hiqdev\billing\hiapi\plan\PlanRepository;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class PlanRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(PlanRepository::class, $this->getRepo());
    }

    protected function getRepo(): PlanRepository
    {
        return $this->getRepository(Plan::class);
    }
}
