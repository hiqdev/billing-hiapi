<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\Hydrator\Helper;

use DateTimeImmutable;
use DateTimeZone;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;

class DateTimeImmutableFormatterStrategyHelper
{
    /**
     * Simplifies DateTimeImmutableFormatterStrategy creation with the
     * options, widely used in the billing-hiapi.
     *
     * @param string $format
     * @param DateTimeZone|null $timezone
     * @return DateTimeImmutableFormatterStrategy
     */
    public static function create(
        string $format = DateTimeImmutable::ATOM,
        ?DateTimeZone $timezone = null
    ): DateTimeImmutableFormatterStrategy
    {
        return new DateTimeImmutableFormatterStrategy(
            new DateTimeFormatterStrategy($format, $timezone, true)
        );
    }
}
