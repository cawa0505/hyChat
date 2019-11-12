<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:39
 */

namespace App\Service;

use App\Constants\ApiCode;
use App\Model\UserFriendModel;
use App\Model\UserModel;
use App\Utility\GenNameFirstLetter;
use Hyperf\Di\Annotation\Inject;

/**
 * Class FriendService
 * @package App\Service
 */
class FriendService extends BaseService
{
    /**
     * @Inject()
     * @var UserModel
     */
    private $userModel;

    /**
     * @Inject()
     * @var UserFriendModel
     */
    private $userFriendModel;

    /**
     * @param $userId
     * @return array
     */
    public function getUserFriend($userId)
    {
        $friend = $this->userFriendModel->getFriendIdsByUserId($userId);
        if (!$friend) {
            return $this->success();
        }
        $friendIds = array_column($friend, 'friend_id');
        $friendInfo = $this->userModel->getUserByUserIds($friendIds, ['id', 'image_url']);
        $result = [];
        foreach ($friend as $key => $item) {
            foreach ($friendInfo as $k => $v) {
                if ($item['friend_id'] == $v['id']) {
                    $result[] = array_merge($item, $v);
                }
            }
        }
        return $this->success($result);
    }


    /**
     * @param $account
     * @return array
     */
    public function searchFriend($account)
    {
        $result = $this->userModel->searchUserByAccount($account);
        return $this->success($result);
    }

    /**
     * 获取好友资料
     * @param $friendId
     * @return array
     */
    public function getFriendInfo($friendId)
    {
        $userFriend = $this->userFriendModel->getFriendIdByFriendId($friendId, ['friend_name']);
        $result = $this->userModel->getUserByUserId($friendId, ['account', 'nick_name', 'sex', 'phone', 'image_url', 'ind_sign']);
        $result['friend_name'] = isset($userFriend['friend_name']) ? $userFriend['friend_name'] : '';
        return $this->success($result);
    }

    /**
     * 修改好友备注
     * @param $request
     * @return array
     */
    public function updateFriendName($request)
    {
        $data = ['friend_name' => $request['friend_name']];
        $result = $this->userFriendModel->updateFriendName($request['friendId'], $data);
        return $this->success($result);
    }

    /**
     * 删除好友
     * @param $friendId
     * @param $userId
     * @return array
     */
    public function deleteFriend($friendId, $userId)
    {
        $result = $this->userFriendModel->deleteFriend($friendId, $userId);
        return $this->success($result);
    }
}