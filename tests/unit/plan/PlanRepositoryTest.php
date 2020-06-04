<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\tests\unit\plan;

use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\plan\PlanRepositoryInterface;
use hiqdev\yii\DataMapper\tests\unit\BaseRepositoryTest;

class PlanRepositoryTest extends BaseRepositoryTest
{
    public function testDI()
    {
        $this->assertInstanceOf(PlanRepositoryInterface::class, $this->getRepo());
    }

    protected function getRepo(): PlanRepositoryInterface
    {
        return $this->getRepository(Plan::class);
    }
}
