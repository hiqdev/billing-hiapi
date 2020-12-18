<?php
declare(strict_types=1);
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\action\Calculate;

use hiqdev\billing\hiapi\action\Calculate\PaidCommand;

class Command extends PaidCommand
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
        ]);
    }
}
