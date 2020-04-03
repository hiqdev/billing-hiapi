<?php

namespace hiqdev\billing\hiapi\provider;

use hiqdev\yii\DataMapper\repositories\BaseRepository;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = ProviderQuery::class;
}
