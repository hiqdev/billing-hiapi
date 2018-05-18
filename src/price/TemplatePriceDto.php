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

use hiqdev\php\billing\price\PriceCreationDto;
use Money\Money;

/**
 * Class TemplatePriceDto.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TemplatePriceDto extends PriceCreationDto
{
    /** @var Money[] */
    public $subprices = [];
}
