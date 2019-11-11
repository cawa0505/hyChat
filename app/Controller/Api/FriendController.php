<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 14:54
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Model\UserModel;
use App\Service\FriendService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FriendController
 * @package App\Controller\Api
 */
class FriendController extends AbstractController
{
    /**
     * @Inject()
     * @var FriendService
     */
    private $friendService;

    /**
     * 好友列表
     * @return ResponseInterface
     */
    public function list()
    {
        $result = $this->friendService->getUserFriend($this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 好友资料
     * @return ResponseInterface
     */
    public function info()
    {
        $result = $this->friendService->getFriendInfo($this->request->post('friendId'));
        return $this->successResponse($result);
    }

    /**
     * 搜索用户
     * @return ResponseInterface
     */
    public function search()
    {
        $account = $this->request->post('account');
        $result = $this->friendService->searchFriend($account);
        return $this->successResponse($result);
    }

    /**
     * 删除好友
     * @return ResponseInterface
     */
    public function delete()
    {
        $friendId = $this->request->post('friendId');
        $result = $this->friendService->deleteFriend($friendId, $this->getUserId());
        return $this->successResponse($result);
    }
}