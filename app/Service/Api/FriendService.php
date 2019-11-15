<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:39
 */

namespace App\Service\Api;

use App\Constants\ApiCode;
use App\Model\UserApplyModel;
use App\Model\UserFriendModel;
use App\Model\UserModel;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
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
     * @Inject()
     * @var UserApplyModel
     */
    private $userApplyModel;

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
        $friendInfo = $this->userModel->getUserByUserIds($friendIds, ['id', 'nick_name', 'image_url']);
        $result = [];
        foreach ($friend as $key => $item) {
            foreach ($friendInfo as $k => $v) {
                if (!$item['friend_name']) {
                    unset($item['friend_name']);
                }
                if ($item['friend_id'] == $v['id']) {
                    $result[] = array_merge($item, $v);
                }
            }
        }
        return $this->success($result);
    }


    /**
     * @param $account
     * @param $userId
     * @return array
     */
    public function searchFriend($account, $userId)
    {
        $result = $this->userModel->searchUserByAccount($account);
        // 获取我的好友
        $userFriend = $this->userFriendModel->getFriendIdsByUserId($userId, ['friend_id']);
        $friendIds = array_column($userFriend, "friend_id");
        foreach ($result as $key => $item) {
            $result[$key]['is_friend'] = 0;
            // 判断搜索的用户是否在自己好友列表中
            if (in_array($item['id'], $friendIds)) {
                $result[$key]['is_friend'] = 1;
            }
            // 判断是否为自己
            if ($item['id'] == $userId) {
                $result[$key]['is_friend'] = 1;
            }
        }
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
        $result = $this->userModel->getUserByUserId($friendId, ['account', 'nick_name', 'sex', 'phone', 'email', 'image_url', 'signature']);
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
        Db::beginTransaction();
        // 删除好友申请记录
        $applyResult = $this->userApplyModel->newQuery()->where('friend_id', $userId)->delete();
        if (!$applyResult) {
            Db::rollBack();
            return $this->fail(ApiCode::DELETE_FRIEND_APPLY_ERROR);
        }
        // 删除好友关系
        $result = $this->userFriendModel->deleteFriend($friendId, $userId);
        if (!$result) {
            Db::rollBack();
            return $this->fail(ApiCode::DELETE_FRIEND_ERROR);
        }
        Db::commit();
        return $this->success($result);
    }
}