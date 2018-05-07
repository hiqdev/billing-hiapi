<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2018, HiQDev (http://hiqdev.com/)
 */

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\console\Application;
use hiqdev\composer\config\Builder;

Yii::setAlias('@root', dirname(__DIR__));
$config = require Builder::path('common');
Yii::$app = new Application($config);
