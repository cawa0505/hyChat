<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/11/2
 * Time: 16:21
 */

namespace App\Controller;


use App\Model\UserModel;
use App\Utility\SendCode;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

class CommonController extends AbstractController
{
    /**
     *  发送验证码
     * @RequestMapping(path="sendCode", methods="post")
     * @return array|ResponseInterface
     */
    public function sendCode()
    {
        $account = $this->request->input('account');
        $user = $this->container->get(UserModel::class)->getUserByAccount($account);
        if (!$user) {
            return $this->fail("用户不存在");
        }
        $result = $this->container->get(SendCode::class)->send();
        return $this->success($result);
    }
}