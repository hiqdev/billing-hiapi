<?php

use hiapi\Core\Http\Psr15\RequestHandler;
use hiapi\Core\Http\Psr7\Response\FatResponse;
use hiqdev\yii\compat\yii;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;

(static function () {
    ini_set('error_reporting', (string)(E_ALL ^ E_NOTICE));
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
