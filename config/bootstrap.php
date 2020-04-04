<?php

use Yiisoft\Composer\Config\Builder;

require_once __DIR__ . '/../../../autoload.php';

date_default_timezone_set('UTC');

$config = require Builder::path('web');
require_once __DIR__ . '/../../../yiisoft/yii2/Yii.php';

\Yii::setAlias('@root', dirname(__DIR__, 4));
\Yii::$app = new \yii\web\Application($config);
