<?php

declare(strict_types=1);

use App\Middleware\Api\JWtMiddleware;
use App\Middleware\AuthorityMiddleware;
use Hyperf\HttpServer\Router\Router;

// TODO Web相关路由分组
Router::addGroup('/', function () {
    require_once BASE_PATH . "/routers/web.php";
});

// TODO Api相关路由分组
Router::addGroup('/api/', function () {
    require_once BASE_PATH . "/routers/api.php";
}, ['middleware' => [JWtMiddleware::class]]);

// TODO Api相关路由分组
Router::addGroup('/admin/', function () {
    require_once BASE_PATH . "/routers/admin.php";
}, ['middleware' => [AuthorityMiddleware::class]]);

// TODO Ws服务
Router::addServer('socket', function () {
    Router::get('/socket', 'App\WebSocket\AppSocketEvent');
});