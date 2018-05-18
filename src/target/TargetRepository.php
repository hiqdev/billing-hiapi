<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\php\billing\target\Target;
use hiqdev\yii\DataMapper\query\Specification;

/**
 * Class TargetRepository
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TargetRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    public $queryClass = TargetQuery::class;

    public function findOneById($id): ?Target
    {
        return $this->findOne((new Specification())->andWhere(['id' => $id]));
    }
}
