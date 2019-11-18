<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/11/2
 * Time: 16:21
 */

namespace App\Controller\Api;

use App\Constants\ApiCode;
use App\Controller\AbstractController;
use App\Model\SysConfig;
use App\Utility\RsaEncryption;
use App\Utility\SendCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @AutoController()
 * Class CommonController
 * @package App\Controller
 */
class CommonController extends AbstractController
{
    /**
     * @Inject()
     * @var RsaEncryption
     */

    private $rsaEncryption;
    /**
     * @Inject()
     * @var SysConfig
     */
    private $sysConfig;

    /*
     * @RequestMapping(path="getPublicKey", methods="post")
     * 获取公钥
     */
    public function getPublicKey()
    {
        $publicKey = $this->rsaEncryption->getPublicKey();
        if (!$publicKey) {
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        dd(config("apiCacheKey"));
        $this->sysConfig->getConfig();
        return $this->response->json($this->success(htmlentities($publicKey)));
    }

    /**
     *  发送验证码
     * @RequestMapping(path="sendCode", methods="post")
     * @return array|ResponseInterface
     */
    public function sendCode()
    {
        $params = $this->request->all();
        $phone = $params['phone'];
        $result = $this->container->get(SendCode::class)->send($phone);
        return $this->successResponse($result);
    }

    /**
     * 上传文件
     * @return ResponseInterface
     */
    public function upload()
    {
        $image = $this->request->file('file');
        $name = uniqid() . '.' . $image->getExtension();
        $destinationPath = BASE_PATH . "/upload/file/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image->moveTo($destinationPath . $name);
        $host = $this->request->getUri()->getHost();
        $ret_data = ['url' => $host . "/upload/file/" . $name, 'path' => '/file/' . $name];
        return $this->successResponse($ret_data);
    }
}