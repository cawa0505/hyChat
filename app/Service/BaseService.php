<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 11:10
 */

namespace App\Service;


use App\Traits\Response;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

class BaseService
{
    use Response;

    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;
}