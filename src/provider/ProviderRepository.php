<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\provider;

use hiqdev\yii\DataMapper\repositories\BaseRepository;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = ProviderQuery::class;
}
