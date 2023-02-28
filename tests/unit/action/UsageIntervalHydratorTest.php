<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\tests\unit\action;

use DateTimeImmutable;
use hiqdev\php\billing\action\UsageInterval;
use hiqdev\yii\DataMapper\tests\unit\BaseHydratorTest;
use Laminas\Hydrator\HydratorInterface;

/**
 * Class UsageIntervalHydratorTest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 * @covers \hiqdev\billing\hiapi\action\UsageIntervalHydrator
 */
class UsageIntervalHydratorTest extends BaseHydratorTest
{
    private HydratorInterface $hydrator;

    public function setUp(): void
    {
        $this->hydrator = $this->getHydrator();
    }

    public function testHydration(): void
    {
        $interval = UsageInterval::withinMonth(
            $month = new DateTimeImmutable('2020-01-01'),
            new DateTimeImmutable('2020-01-24 22:11:00'),
            null
        );

        $hydratedInterval = $this->hydrator->hydrate([
            'month' => $month->format(DATE_ATOM),
            'start' => $interval->start()->format(DATE_ATOM),
            'end' => $interval->end()->format(DATE_ATOM),
        ], UsageInterval::class);

        $this->assertInstanceOf(UsageInterval::class, $interval);
        $this->assertEquals($interval, $hydratedInterval);
    }

    public function testExtraction(): void
    {
        $interval = UsageInterval::withinMonth(
            $month = new DateTimeImmutable('2020-01-01'),
            new DateTimeImmutable('2020-01-24 22:11:00'),
            null
        );

        $extracted = $this->hydrator->extract($interval);
        $this->assertSame([
            'start' => '2020-01-24T22:11:00+00:00',
            'end' => '2020-02-01T00:00:00+00:00',
            'seconds' => 611340,
            'ratio' => 0.22824820788530467,
            'seconds_in_month' => 2678400
        ], $extracted);
    }
}
