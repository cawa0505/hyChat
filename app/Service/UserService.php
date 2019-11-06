<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 13:24
 */

namespace App\Service;

use App\Constants\ApiCode;
use App\Model\UserModel;
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
        $token = container()->get(Token::class)->encode($userInfo);
        /** @var Common $socketCommon */
        $socketCommon = container()->get(Common::class);
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
            return $this->fail(ApiCode::AUTH_USER_EXIST);
        }
        $data = [
            'account' => $account,
            'phone' => $phone,
            'password' => makePasswordHash($password),
            'create_time' => time()
        ];
        $result = $this->userModel->createAccount($data);
        if (!$result) {
            return $this->fail(ApiCode::AUTH_REGISTER_ERR);
        }
        $key = 'mobileVerifyCode:' . $phone;
        redis()->del($key);
        $response = $this->userModel->getUserByAccount($account);
        return $this->success($response);
    }

    /**
     * 通过手机号修改密码
     * @param $phone
     * @param $password
     * @return array
     */
    public function updatePasswordByPhone($phone, $password)
    {
        $result = $this->userModel->updatePasswordByPhone($phone, makePasswordHash($password));
        if (!$result) {
            return $this->fail("密码修改失败");
        }
        return $this->success($result);
    }
}