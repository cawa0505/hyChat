<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:55
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Model\UserFriendModel;
use App\Model\UserGroupModel;
use App\Model\UserModel;
use App\Service\ApplyService;
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
     * 用户详情
     * @return ResponseInterface
     */
    public function info()
    {
        $userId = getContext('userId');
        $result = $this->userModel->getUserByUserId($userId);
        return $this->success($result);
    }

    /**
     * 我的好友
     * @return ResponseInterface
     */
    public function friend()
    {
        $userId = getContext('userId');
        $result = $this->container->get(UserFriendModel::class)->getUserFriend($userId);
        return $this->success($result);
    }

    /**
     * 我的群组
     * @return ResponseInterface
     */
    public function group()
    {
        $userId = getContext('userId');
        $result = $this->container->get(UserGroupModel::class)->getGroupByUserId($userId);
        return $this->success($result);
    }

    /**
     * 我的申请
     * @return ResponseInterface
     */
    public function apply()
    {
        $userId = getContext('userId');
        $result = $this->container->get(ApplyService::class)->getApplyByUserId($userId);
        return $this->success($result);
    }
}