<?php

declare(strict_types=1);

use App\Middleware\Api\TokenMiddleware;
use Hyperf\HttpServer\Router\Router;

// TODO Web相关路由分组
Router::addGroup('/', function () {
    require_once BASE_PATH . "/routers/web.php";
});

// TODO Api相关路由分组
Router::addGroup('/api/', function () {
    require_once BASE_PATH . "/routers/api.php";
}, ['middleware' => [TokenMiddleware::class]]);

// TODO Ws服务
Router::addServer('wsApp', function () {
    Router::get('/wsApp', 'App\WebSocket\Event\AppSocketEvent');
});

// TODO Ws服务
Router::addServer('wsPc', function () {
    Router::get('/wsPc', 'App\WebSocket\Event\PcSocketEvent');
});