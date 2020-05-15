<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\target;

use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\target\TargetRepositoryInterface;
use hiqdev\yii\DataMapper\query\Specification;

/**
 * Class TargetRepository.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TargetRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository implements TargetRepositoryInterface
{
    public $queryClass = TargetQuery::class;

    public function findOneById($id): ?Target
    {
        return $this->findOne((new Specification())->andWhere(['id' => $id]));
    }

    public function save(TargetInterface $target)
    {
        $params = [
            ':type'         => $target->getType(),
            ':name'         => $target->getName(),
            ':remoteid'     => $target->getRemoteId(),
            ':client_id'    => $target->getCustomer()->getId(),
        ];
        $command = $this->db->createCommand('
            INSERT INTO target (client_id, type_id, name, remoteid)
            VALUES (:client_id, class_id(:type), :name, :remoteid)
            RETURNING obj_id
        ', $params);
        $target->setId($command->queryScalar());
    }
}
