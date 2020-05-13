<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

use hiapi\Core\Http\Psr15\RequestHandler;
use hiapi\Core\Http\Psr7\Response\FatResponse;
use hiqdev\yii\compat\yii;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;

(static function () {
    ini_set('error_reporting', (string) (E_ALL ^ E_NOTICE));
    define('APP_TYPE', 'web');
    require_once __DIR__ . '/../config/bootstrap.php';

    $container = yii::getContainer();
    $runner = new RequestHandlerRunner(
        $container->get(RequestHandler::class),
        $container->get(SapiEmitter::class),
        static function (): ServerRequest {
            return ServerRequestFactory::fromGlobals();
        },
        static function (Throwable $e) {
            return FatResponse::create($e);
        }
    );
    $runner->run();
})();
