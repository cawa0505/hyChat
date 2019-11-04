<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 13:24
 */

namespace App\Service;

use App\Model\UserModel;
use App\Utility\SendCode;
use App\WebSocket\Common;
use App\Utility\Token;
use Hyperf\Di\Annotation\Inject;

class UserService extends BaseService
{
    /**
     * @Inject()
     * @var UserModel
     */
    protected $userModel;

    /**
     * 处理用户登录
     * @param $request
     * @return mixed
     */
    public function handleLogin($request)
    {
        $account = $request['account'];
        $password = $request['password'];
        $userInfo = $this->userModel->getUserByAccount($account);
        if (!$userInfo) {
            return $this->fail("账号不存在");
        }
        if (!$userInfo['status']) {
            return $this->fail("该账户已被锁定");
        }
        if (!validatePasswordHash($password, $userInfo['password'])) {
            return $this->fail("用户名密码不匹配");
        }
        unset($userInfo['password']);
        // 单点登陆 给前一个设备推送消息
        $token = $this->container->get(Token::class)->encode($userInfo);
        /** @var Common $socketCommon */
        $socketCommon = $this->container->get(Common::class);
        $userFd = $socketCommon->getUserFd($userInfo['id']);
        if ($userFd) {
            $socketCommon->sendTo($userFd, $this->sendMessage(1, [], "已在别处登陆"));
        }
        // 保存用户信息
        redis()->hSet("userToken", (string)$userInfo['id'], $token);
        return $this->success($token);
    }

    /**
     * 处理注册
     * @param $request
     * @return array
     */
    public function handleRegister($request)
    {
        $account = $request['account'];
        $password = $request['password'];
        $phone = $request['phone'];
        $user = $this->userModel->getUserByAccount($account);
        if ($user) {
            return $this->fail("此用户已存在");
        }
        $data = [
            'account' => $account,
            'phone' => $phone,
            'password' => makePasswordHash($password),
            'create_time' => time()
        ];
        $result = $this->userModel->createAccount($data);
        if (!$result) {
            return $this->fail("注册失败");
        }
        $response = $this->userModel->getUserByAccount($account);
        return $this->success($response);
    }

    /**
     * 找回密码
     * @param $request
     * @return array
     */
    public function updatePassword($request)
    {
        $phone = $request['phone'];
        $password = makePasswordHash($request['password']);
        $result = $this->userModel->updatePasswordByPhone($phone, $password);
        if (!$result) {
            return $this->fail("密码修改失败");
        }
        return $this->success($result);
    }
}