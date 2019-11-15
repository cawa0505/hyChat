<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 14:58
 */

use App\Controller\Admin\AuthController;
use App\Controller\IndexController;
use Hyperf\HttpServer\Router\Router;

// 登陆
Router::post('auth/login', [AuthController::class, 'login']);
// 退出
Router::post('auth/logout', [AuthController::class, 'logout']);