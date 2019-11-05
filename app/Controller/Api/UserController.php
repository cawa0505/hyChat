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
        $result = $this->userModel->getUserByUserId($this->getUserId());
        return $this->success($result);
    }

    /**
     * 我的好友
     * @return ResponseInterface
     */
    public function friend()
    {
        $result = $this->container->get(UserFriendModel::class)->getUserFriend($this->getUserId());
        return $this->success($result);
    }

    /**
     * 我的群组
     * @return ResponseInterface
     */
    public function group()
    {
        $result = $this->container->get(UserGroupModel::class)->getGroupByUserId($this->getUserId());
        return $this->success($result);
    }

    /**
     * 我的申请
     * @return ResponseInterface
     */
    public function apply()
    {
        $result = $this->container->get(ApplyService::class)->getApplyByUserId($this->getUserId());
        return $this->success($result);
    }
}