<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:55
 */
declare(strict_types=1);

namespace App\Controller\Api;

use App\Constants\ApiCode;
use App\Controller\AbstractController;
use App\Model\UserModel;
use App\Request\Api\UserRequest;
use App\Service\Api\UserService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 * @package App\Controller\Api
 */
class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var UserModel
     */
    private $userModel;
    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    /**
     * 用户详情
     * @return ResponseInterface
     */
    public function info()
    {
        $result = $this->userModel->getUserByUserId($this->getUserId());
        unset($result['password']);
        return $this->successResponse($this->success($result));
    }

    /**
     * 更新用户信息
     * @param UserRequest $request
     * @return ResponseInterface
     */
    public function updateUserInfo(UserRequest $request)
    {
        $param = $request->post();

        if (count($param) < 1) return $this->errorResponse($this->fail(ApiCode::PARAMS_NOT_EXIST));

        $result = $this->userService->updateUserInfo($param, $this->getUserId());

        return $this->successResponse($result);
    }
}