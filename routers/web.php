<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 14:58
 */

use App\Controller\IndexController;
use Hyperf\HttpServer\Router\Router;

Router::get('', [IndexController::class, 'index']);