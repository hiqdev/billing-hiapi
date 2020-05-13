<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\vo;

use DateTimeImmutable;
use hiqdev\yii\DataMapper\repositories\BaseRepository;

class DateTimeImmutableRepository extends BaseRepository
{
    /**
     * @param array $row
     * @return DateTimeImmutable
     */
    public function create($data)
    {
        return new DateTimeImmutable($data);
    }
}
