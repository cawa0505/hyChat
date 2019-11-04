<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:03
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Model\UserModel;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AuthController
 * @package App\Controller\Api
 */
class AuthController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * 用户登录
     * @return ResponseInterface
     */
    public function login()
    {
        $request = $this->request->getParsedBody();
        $rules = [
            'account' => 'required|min:8|alpha_num',
            'password' => 'required|min:8|alpha_dash',
        ];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors();
            return $this->error($errorMessage);
        }
        $response = $this->userService->handleLogin($request);
        return $this->success($response);
    }

    /**
     * 用户注册
     * @return ResponseInterface
     */
    public function register()
    {
        $request = $this->request->getParsedBody();
        $rules = [
            'account' => 'required|min:8|alpha_num',
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|min:8|alpha_dash',
            'code' => 'required|numeric|digits:6',
        ];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors();
            return $this->error($errorMessage);
        }

//        $cacheCode = redis()->get($request['phone']);
//        if (!$cacheCode) {
//            return $this->error("验证码无效");
//        }
//        if ($cacheCode != $request['code']) {
//            return $this->error("验证码不匹配");
//        }
        $response = $this->userService->handleRegister($request);
        return $this->success($response);
    }

    /**
     * 用户退出
     * @return ResponseInterface
     */
    public function logout()
    {
        $userId = getContext('userId');
        redis()->hDel('userToken', (string)$userId);
        return $this->success();
    }

    /**
     * 通过手机号找回密码
     * @return ResponseInterface
     */
    public function retrieve()
    {
        $request = $this->request->getParsedBody();
        $rules = [
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|min:8|alpha_dash',
        ];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors();
            return $this->error($errorMessage);
        }
        $response = $this->userService->updatePassword($request);
        return $this->success($response);
    }
}