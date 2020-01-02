<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\charge;

use hiqdev\php\billing\action\TemporaryAction;
use hiqdev\php\billing\charge\Charge;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\models\relations\Bucket;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use League\Event\EmitterInterface;
use yii\db\Query;

class ChargeRepository extends BaseRepository
{
    /**
     * @var EmitterInterface
     */
    private $emitter;

    public function __construct(
        ConnectionInterface $db,
        EntityManagerInterface $em,
        EmitterInterface $emitter,
        array $config = []
    ) {
        parent::__construct($db, $em, $config);

        $this->emitter = $emitter;
    }

    /** {@inheritdoc} */
    public $queryClass = ChargeQuery::class;

    public function save(Charge $charge)
    {
        $action = $charge->getAction();
        $tariff_id = null;
        if ($action->hasSale($action)) {
            $tariff_id = $action->getSale()->getPlan()->getId();
            $i = 0;
            while ($action instanceof TemporaryAction) {
                $action = $action->getParent();
                if ($i++ > 10) {
                    throw new \RuntimeException('Temporary action nesting limit has been exceeded.');
                }
            }

            $this->em->save($action);
        }

        $hstore = new HstoreExpression(array_filter([
            'id'            => $charge->getId(),
            'object_id'     => $charge->getTarget()->getId(),
            'tariff_id'     => $tariff_id,
            'action_id'     => $action->getId(),
            'buyer_id'      => $action->getCustomer()->getId(),
            'buyer'         => $action->getCustomer()->getLogin(),
            'type_id'       => $charge->getType()->getId(),
            'type'          => $charge->getType()->getName(),
            'currency'      => $charge->getSum()->getCurrency()->getCode(),
            'sum'           => $charge->getSum()->getAmount(),
            'unit'          => $charge->getUsage()->getUnit()->getName(),
            'quantity'      => $charge->getUsage()->getQuantity(),
            'bill_id'       => $charge->getBill()->getId(),
            'parent_id'     => $charge->getParent() !== null ? $charge->getParent()->getId() : null,
            'time'          => $charge->getAction()->getTime()->format('c'),
            'is_finished'   => $charge->isFinished(),
            'label'         => $charge->getComment(),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH));
        $call = new CallExpression('set_charge', [$hstore]);
        $command = (new Query())->select($call);
        $charge->setId($command->scalar($this->db));
        $events = $charge->releaseEvents();
        if (!empty($events)) {
            $this->emitter->emitBatch($events);
        }
    }

    protected function joinParent(&$rows)
    {
        $bucket = Bucket::fromRows($rows, 'parent-id');
        $spec = (new Specification())->where(['id' => $bucket->getKeys()]);
        $charges = $this->getRepository(ChargeInterface::class)->queryAll($spec);
        $bucket->fill($charges, 'id');
        $bucket->pourOneToOne($rows, 'parent');
    }
}
