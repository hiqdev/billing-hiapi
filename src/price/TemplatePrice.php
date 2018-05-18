<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\price;

use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\price\SinglePrice;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\QuantityInterface;
use Money\Money;

/**
 * Class TemplatePrice.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TemplatePrice extends SinglePrice
{
    /**
     * @var Money[]
     */
    protected $subprices = [];

    public function __construct(
        $id,
        TypeInterface $type,
        TargetInterface $target,
        PlanInterface $plan = null,
        QuantityInterface $prepaid,
        Money $price,
        array $subprices
    ) {
        parent::__construct($id, $type, $target, $plan, $prepaid, $price);

        $this->subprices = $subprices;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'subprices' => $this->subprices,
        ]);
    }

    /**
     * @return Money[]
     */
    public function getSubprices(): array
    {
        return $this->subprices;
    }
}
