<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/5
 * Time: 13:44
 */

namespace App\Middleware\Api;


use App\Traits\Response;
use App\Utility\RsaEncryption;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DecryptMiddleware implements MiddlewareInterface
{
    use Response;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * TokenMiddleware constructor.
     * @param HttpResponse $response
     * @param RequestInterface $request
     */
    public function __construct(HttpResponse $response, RequestInterface $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($request->encrypt)) {
            return $this->response->json($this->fail("encrypt 不能为空"));
        }
        /** @var RsaEncryption $rsa */
        $rsa = container()->get(RsaEncryption::class);
        $rsaArray = $rsa->privateDecrypt($request->encrypt);
        if (!is_array($rsaArray)) {
            logger("rsa")->error(json_encode($rsaArray));
            return $this->response->json($this->fail("解析失败"));
        }
        unset($request->encrypt);
        $request->withParsedBody($rsaArray);
        return $handler->handle($request);
    }
}