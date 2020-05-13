<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

use Yiisoft\Composer\Config\Builder;

require_once __DIR__ . '/../../../autoload.php';

date_default_timezone_set('UTC');

$config = require Builder::path('web');
require_once __DIR__ . '/../../../yiisoft/yii2/Yii.php';

\Yii::setAlias('@root', dirname(__DIR__, 4));
\Yii::$app = new \yii\web\Application($config);
