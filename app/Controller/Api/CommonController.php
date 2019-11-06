<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/11/2
 * Time: 16:21
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Utility\RsaEncryption;
use App\Model\UserModel;
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
            return $this->fail("公钥获取失败");
        }
        return $this->response->raw($publicKey);
    }

    /**
     *  发送验证码
     * @RequestMapping(path="sendCode", methods="post")
     * @return array|ResponseInterface
     */
    public function sendCode()
    {
        $mobile = $this->request->input('mobile');
        $user = $this->container->get(UserModel::class)->getUserByAccount($mobile);
        if (!$user) {
            return $this->fail("用户不存在");
        }
        $result = $this->container->get(SendCode::class)->send($mobile);
        return $this->successResponse($result);
    }
}