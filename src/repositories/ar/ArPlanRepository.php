<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\repositories\ar;

use hiqdev\yii\DataMapper\query\QueryMutator;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\billing\hiapi\models\Plan;
use hiqdev\billing\hiapi\models\Price;
use hiqdev\billing\hiapi\repositories\PlanCreationDto;
use hiqdev\billing\hiapi\repositories\PlanFactory;
use hiqdev\billing\hiapi\repositories\PriceCreationDto;
use hiqdev\billing\hiapi\repositories\PriceFactory;

class ArPlanRepository extends \hiqdev\yii\DataMapper\repositories\BaseRepository
{
    /**
     * @var PlanFactory
     */
    private $planFactory;
    /**
     * @var PriceFactory
     */
    private $priceFactory;

    public function __construct(
        PlanFactory $planFactory,
        PriceFactory $priceFactory,
        array $config = []
    ) {
        parent::__construct($config);

        $this->planFactory = $planFactory;
        $this->priceFactory = $priceFactory;
    }

    public function findAll(Specification $specification)
    {
        $mutator = (new QueryMutator(Plan::find()
            ->with(['prices', 'type', 'target' => function ($query) {
                return $query->with('type');
            }])
        ))->apply($specification);

        $plans = $this->extractEntities($mutator->getQuery()->all());

        return $plans;
    }

    protected function extractEntities($plans)
    {
        $result = [];

        foreach ($plans as $plan) {
            $dto = new PlanCreationDto();
            $dto->id = $plan->obj_id;
            $dto->name = $plan->name;
            $dto->seller = $plan->seller->getEntity(); // todo: get rid
            $dto->prices = array_map(function ($price) {
                /** @var Price $price */
                $dto = new PriceCreationDto();
                $dto->id = $price->id;
                $dto->type_id = $price->type->obj_id;
                $dto->type = $price->type->name;
                $dto->target_id = $price->target->obj_id;
                $dto->target_name = $price->target->label;
                $dto->target_type_id = $price->target->type->obj_id;
                $dto->target_type_name = $price->target->type->name;
                $dto->quantity = $price->quantity;
                $dto->unit = $price->unit->name;
                $dto->currency = $price->currency->name;
                $dto->price = intval($price->price); // todo: fix

                return $this->priceFactory->createByDto($dto);
            }, $plan->prices);

            $result[] = $this->planFactory->create($dto);
        }

        return $result;
    }
}
