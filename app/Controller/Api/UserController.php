<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:55
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Model\UserGroupModel;
use App\Model\UserModel;
use App\Request\UserRequest;
use App\Service\ApplyService;
use App\Service\FriendService;
use App\Service\UserService;
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
     * 我的好友
     * @return ResponseInterface
     */
    public function friend()
    {
        $result = $this->container->get(FriendService::class)->getUserFriend($this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 我的群组
     * @return ResponseInterface
     */
    public function group()
    {

        $result = $this->container->get(UserGroupModel::class)->getGroupByUserId($this->getUserId());
        return $this->successResponse($this->success($result));
    }

    /**
     * 我的申请
     * @return ResponseInterface
     */
    public function apply()
    {
        $result = $this->container->get(ApplyService::class)->getApplyByUserId($this->getUserId());
        return $this->successResponse($this->success($result));
    }

    /**
     * 更新用户信息
     * @param UserRequest $request
     * @return ResponseInterface
     */
    public function updateUserInfo(UserRequest $request)
    {
        $result = $this->userService->updateUserInfo($request->all(), $this->getUserId());
        return $this->successResponse($this->success($result));
    }
}