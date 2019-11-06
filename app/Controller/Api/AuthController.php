<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:03
 */

namespace App\Controller\Api;

use App\Constants\ApiCode;
use App\Controller\AbstractController;
use App\Request\Auth\RegisterRequest;
use App\Request\Auth\LoginRequest;
use App\Request\Auth\RetrieveRequest;
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
     * @param LoginRequest $request
     * @return ResponseInterface
     */
    public function login(LoginRequest $request)
    {
        $response = $this->userService->handleLogin($request->all());
        return $this->successResponse($response);
    }

    /**
     * 用户注册
     * @param RegisterRequest $request
     * @return ResponseInterface
     */
    public function register(RegisterRequest $request)
    {
        $phone = $request->input('phone');
        $key = 'mobileVerifyCode:' . $phone;
        $cacheCode = redis()->get($key);
        if (!$cacheCode) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        if ($cacheCode != $request->input('code')) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        $response = $this->userService->handleRegister($request->all());
        return $this->successResponse($response);
    }

    /**
     * 用户退出
     * @return ResponseInterface
     */
    public function logout()
    {
        redis()->hDel('userToken', (string)$this->getUserId());
        return $this->successResponse();
    }

    /**
     * 忘记密码修改密码
     * @param RetrieveRequest $request
     * @return ResponseInterface
     */
    public function retrieve(RetrieveRequest $request)
    {
        $phone = $request->input('phone');
        $key = 'mobileVerifyCode:' . $phone;
        $cacheCode = redis()->get($key);
        if (!$cacheCode) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        if ($cacheCode != $request->input('code')) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        $response = $this->userService->updatePasswordByPhone($phone, $request->input('password'));
        return $this->successResponse($response);
    }
}