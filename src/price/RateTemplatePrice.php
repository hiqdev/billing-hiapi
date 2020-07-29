<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\QuantityInterface;
use Money\Money;

/**
 * Class TemplateRatePrice.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class RateTemplatePrice extends SinglePrice
{
    /**
     * @var float
     */
    protected float $rate;

    public function __construct(
        $id,
        TypeInterface $type,
        TargetInterface $target,
        PlanInterface $plan = null,
        QuantityInterface $prepaid,
        Money $price,
        float $rate
    ) {
        parent::__construct($id, $type, $target, $plan, $prepaid, $price);

        $this->rate = $rate;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
