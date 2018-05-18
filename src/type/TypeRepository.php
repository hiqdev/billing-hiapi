<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\type;

use hiqdev\php\billing\target\TargetFactoryInterface;
use hiqdev\php\billing\type\TypeFactoryInterface;
use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;

/**
 * Class TypeRepository
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TypeRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /**
     * @var TypeFactoryInterface
     */
    protected $factory;

    /** {@inheritdoc} */
    public $queryClass = TypeQuery::class;

    public function __construct(
        ConnectionInterface $db,
        EntityManagerInterface $em,
        TargetFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($db, $em, $config);

        $this->factory = $factory;
    }
}
