<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\models;

use Money\Currency as Entity;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord
{
    public static function tableName()
    {
        return 'zref';
    }

    public function getEntity()
    {
        return new Entity($this->obj_id, $this->name);
    }
}
