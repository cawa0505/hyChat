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
use App\Utility\Token;
use App\WebSocket\Service\CommonServer;
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
        // type 1app 2pc
        $type = $request['type'];
        $account = $request['account'];
        $password = $request['password'];
        $userInfo = $this->userModel->getUserByAccount($account);
        if (!$userInfo) {
            return $this->fail(ApiCode::AUTH_USER_NOT_EXIST);
        }
        if (!$userInfo['status']) {
            return $this->fail(ApiCode::AUTH_USER_LOCK);
        }
        if (!validatePasswordHash($password, $userInfo['password'])) {
            return $this->fail(ApiCode::AUTH_PASS_ERR);
        }
        unset($userInfo['password']);
        $userInfo['login_type'] = $type;
        // 单点登陆 给前一个设备推送消息
        $token = container()->get(Token::class)->encode($userInfo);
        /** @var CommonServer $socketCommon */
        $socketCommon = container()->get(CommonServer::class);
        $userFd = $socketCommon->getUserFd($userInfo['id']);
        if ($userFd) {
            $socketCommon->sendTo($userFd, $this->sendMessage(1, [], "已在别处登陆"));
        }
        // 保存用户信息
        redis()->hSet("userToken", $userInfo['id'] . "_" . $type, $token);
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
            return $this->fail(ApiCode::AUTH_PASS_EDIT_ERR);
        }
        $key = 'mobileVerifyCode:' . $phone;
        redis()->del($key);
        return $this->success($result);
    }

    /**
     * 编辑用户信息
     * @param $params
     * @param $user_id
     * @return array
     */
    public function updateUserInfo($params, $user_id)
    {
        $user = $this->userModel->getUserByUserIds($user_id);
        if (!$user) {
            return $this->fail(ApiCode::AUTH_USER_NOT_EXIST);
        }
        $result = $this->userModel->updateUserInfo($params, $user_id);
        if (!$result) {
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success($result);
    }
}