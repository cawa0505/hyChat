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
Router::addServer('app', function () {
    Router::get('/app', 'App\WebSocket\Event\AppSocketEvent');
});

// TODO Ws服务
Router::addServer('pc', function () {
    Router::get('/pc', 'App\WebSocket\Event\PcSocketEvent');
});