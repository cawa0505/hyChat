<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 14:54
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
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
     * 搜索用户
     * @return ResponseInterface
     */
    public function search()
    {
        $request = $this->request->all();
        $result = $this->friendService->searchFriend($request);
        return $this->successResponse($result);
    }

    /**
     * 删除好友
     * @return ResponseInterface
     */
    public function delete()
    {
        $friendId = $this->request->input('friendId');
        $result = $this->friendService->deleteFriend($friendId, $this->getUserId());
        return $this->successResponse($result);
    }
}