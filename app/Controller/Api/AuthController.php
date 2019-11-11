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
use App\Request\AuthRequest;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

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
     * @param AuthRequest $request
     * @return ResponseInterface
     */
    public function login(AuthRequest $request)
    {
        $response = $this->userService->handleLogin($request->all());
        return $this->successResponse($response);
    }

    /**
     * 用户注册
     * @param AuthRequest $request
     * @return ResponseInterface
     */
    public function register(AuthRequest $request)
    {
        $params=$request->all();
        $phone = $params['phone'];
        //验证码
        $key = 'phoneVerifyCode:' . $phone;
        $cacheCode = redis()->get($key);
        if (!$cacheCode) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        if ($cacheCode != $params['code']) {
            return $this->errorResponse(ApiCode::AUTH_CODE_NOT_EXIST);
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
        $result = redis()->hDel('userToken', $this->getUserId() . "_" . getContext('login_type'));

        return $this->successResponse([$result]);
    }

    /**
     * 忘记密码修改密码
     * @param AuthRequest $request
     * @return ResponseInterface
     */
    public function retrieve(AuthRequest $request)
    {
        $phone = $request->post('phone');
        $key = 'phoneVerifyCode:' . $phone;
        $cacheCode = redis()->get($key);
        if (!$cacheCode) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        if ($cacheCode != $request->post('code')) {
            return $this->errorResponse(ApiCode::AUTH_CODE_ERROR);
        }
        $response = $this->userService->updatePasswordByPhone($phone, $request->post('password'));
        return $this->successResponse($response);
    }

    /**
     * 扫码登录
     */
    public function scanLogin()
    {
        
    }
}