<?php

namespace hiqdev\billing\hiapi\tests\unit\bill;

use hiqdev\php\billing\bill\Bill;
use hiqdev\billing\hiapi\bill\BillRepository;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;

class BillRepositoryTest extends \PHPUnit\Framework\TestCase
{
    public function testDI()
    {
        $this->assertInstanceOf(BillRepository::class, $this->getRepo());
    }

    protected function getRepo(): BillRepository
    {
        return $this->getEntityManager()->getRepository(Bill::class);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get(EntityManagerInterface::class);
    }

    protected function getContainer()
    {
        return class_exists('Yii') ? \Yii::$container : \yii\helpers\Yii::getContainer();
    }
}
