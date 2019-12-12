<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 14:58
 */

use App\Controller\Admin\AdminController;
use App\Controller\Admin\AuthController;
use App\Controller\Admin\PermissionController;
use App\Controller\Admin\RoleController;
use Hyperf\HttpServer\Router\Router;

// 登陆
Router::post('auth/login', [AuthController::class, 'login']);
// 退出
Router::post('auth/logout', [AuthController::class, 'logout']);


Router::post('admin/list', [AdminController::class, 'list']);
Router::post('admin/create', [AdminController::class, 'create']);
Router::post('admin/update', [AdminController::class, 'update']);
Router::post('admin/delete', [AdminController::class, 'delete']);

Router::post('role/list', [RoleController::class, 'list']);
Router::post('role/create', [RoleController::class, 'create']);
Router::post('role/update', [RoleController::class, 'update']);
Router::post('role/delete', [RoleController::class, 'delete']);

Router::post('permission/list', [PermissionController::class, 'list']);
Router::post('permission/create', [PermissionController::class, 'create']);
Router::post('permission/update', [PermissionController::class, 'update']);
Router::post('permission/delete', [PermissionController::class, 'delete']);