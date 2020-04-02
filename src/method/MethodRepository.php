<?php

namespace hiqdev\billing\hiapi\method;

use hiqdev\yii\DataMapper\repositories\BaseRepository;

class MethodRepository extends BaseRepository implements MethodRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = MethodQuery::class;
}
