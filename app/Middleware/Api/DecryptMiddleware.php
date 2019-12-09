<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/5
 * Time: 13:44
 */
declare(strict_types=1);

namespace App\Middleware\Api;


use App\Constants\SystemCode;
use App\Traits\Response;
use App\Utility\CheckSign;
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
     * @var array
     */
    protected $whiteList = [
        '/',
        '/api/auth/login',
    ];

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
        $requestUri = $request->getUri()->getPath();
        if (!in_array($requestUri, $this->whiteList)) {
            $param = $request->getParsedBody();
            //私钥解密
            if (!isset($param['encrypt'])) {
                return $this->response->json($this->fail("encrypt 不能为空"));
            }

            /** @var RsaEncryption $rsa */
            $rsa = container()->get(RsaEncryption::class);
            $rsaArray = $rsa->privateDecrypt($param['encrypt']);

            if (!is_array($rsaArray)) {
                logger("rsa")->error(json_encode($rsaArray));
                return $this->response->json($this->fail("解析失败"));
            }

            //参数验签
            if (!isset($rsaArray["sign"])) {
                return $this->response->json($this->fail("sign 不能为空"));
            }

            $request = $request->withParsedBody($rsaArray);
            /** @var ServerRequestInterface $server */
            setContext(ServerRequestInterface::class, $request);

            /** @var CheckSign $sign */
            $sign = container()->get(CheckSign::class);
            if (!$sign->checkSign($rsaArray)) {
                return $this->response->json($this->fail("签名错误"))->withStatus(SystemCode::UNPROCESSABLE_ENTITY);
            };
        }
        return $handler->handle($request);
    }
}