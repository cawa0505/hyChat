<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 16:36
 */

namespace App\Controller;

use App\Traits\Response;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController
{
    use Response;
    /**
     * 容器
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 视图
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * 验证器
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function successResponse($data = [])
    {
        return $this->response->json($data);
    }

    /**
     * @param $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function errorResponse($message)
    {
        return $this->response->json($this->fail($message));
    }
}