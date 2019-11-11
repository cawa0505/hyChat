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
        $mobile = $params['mobile'];
        $result = $this->container->get(SendCode::class)->send($mobile);
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
        $demine = $this->request->getUri()->getHost();
        $ret_data = ['url' => $demine . "/upload/file/" . $name, 'path' => '/file/' . $name];
        return $this->successResponse($ret_data);
    }
}