<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 13:24
 */

declare(strict_types=1);

namespace App\Service\Api;

use App\Constants\ApiCode;
use App\Constants\MessageCode;
use App\Model\UserModel;
use App\Service\BaseService;
use App\Traits\PushMessage;
use App\Utility\Random;
use App\Utility\Token;
use Hyperf\Di\Annotation\Inject;

class UserService extends BaseService
{
    use PushMessage;
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
        // 登录方式 1 账号密码 2 手机号验证码
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

        $userToken = redis()->hGet("userToken", $userInfo['id'] . "_" . $type);
        if ($userToken) {
            /** @var \App\WebSocket\Service\UserService $socketCommon */
            $socketCommon = container()->get(\App\WebSocket\Service\UserService::class);
            $userFd = $socketCommon->getUserFd($userInfo['id'], $type);
            if ($userFd) {
                $this->sendToUser($this->sendMessage(MessageCode::LOGOUT),$userInfo['id']);
            }
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
        $url = sprintf("http://192.168.0.163/images/%d.jpg", rand(1, 6));
        $data = [
            'nick_name' => Random::character(10),
            'image_url' => $url,
            'account' => $account,
            'phone' => $phone,
            'password' => makePasswordHash($password),
            'create_time' => time()
        ];
        $result = $this->userModel->createAccount($data);
        if (!$result) {
            return $this->fail(ApiCode::AUTH_REGISTER_ERR);
        }
        //验证
        $key = 'phoneVerifyCode:' . $phone;
        redis()->del($key);
        $response = $this->userModel->getUserByAccount($account);
        unset($response['password']);
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
        $key = 'phoneVerifyCode:' . $phone;
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

        $user = $this->userModel->getUserByUserIds([$user_id]);

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