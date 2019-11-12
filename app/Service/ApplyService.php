<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:06
 */

namespace App\Service;

use App\Constants\ApiCode;
use App\Constants\SystemCode;
use App\Model\UserApplyModel;
use App\Model\UserFriendModel;
use App\Model\UserModel;
use Hyperf\Di\Annotation\Inject;

/**
 * Class ApplyService
 * @package App\Service
 */
class ApplyService extends BaseService
{
    /**
     * @Inject()
     * @var UserApplyModel
     */
    private $userApplyModel;

    /**
     * 添加好友申请
     * @param $request
     * @param $userId
     * @return array
     */
    public function createApply($request, $userId)
    {
        if ($request['friendId'] == $userId) {
            return $this->fail(ApiCode::CANT_ADD_SELF);
        }
        $data = [
            'apply_user_id' => $userId,
            'user_id' => $request['friendId'],
            'create_time' => time()
        ];
        if (isset($request['message']) && $request['message']) {
            $data['message'] = $request['message'];
        }
        // 创建申请记录
        $result = $this->userApplyModel->create($data);
        // 发送申请提醒
        $this->sendToUser($request['friendId'], $this->sendMessage(SystemCode::SUCCESS, [], $request['message']));
        return $this->success($result);
    }

    /**
     * 通过用户id获取申请
     * @param $userId
     * @return array
     */
    public function getApplyByUserId($userId)
    {
        $applyResult = $this->userApplyModel->getApplyByUserId($userId, ['apply_user_id as friend_id', 'message', 'status']);
        if (!$applyResult) {
            return $this->success();
        }
        $applyUserId = array_column($applyResult, 'friend_id');
        $applyUserIdInfo = container()->get(UserModel::class)->getUserByUserIds($applyUserId, ['id', 'nick_name', 'image_url']);
        $result = [];
        foreach ($applyResult as $key => $item) {
            foreach ($applyUserIdInfo as $k => $v) {
                if ($item['friend_id'] == $v['id']) {
                    unset($v['id']);
                    $result[] = array_merge($item, $v);
                }
            }
        }
        return $this->success($result);
    }

    /**
     * 申请审核
     * @param $request
     * @param $userId
     * @return array
     */
    public function reviewApply($request, $userId)
    {
        // TODO status 1 通过 2 拒绝
        if ($request['status'] == 2) {
            echo "拒绝";
        }
        /** @var UserApplyModel $userApply */
        $userApply = container()->get(UserApplyModel::class);
        $applyResult = $userApply->getApplyById($request['applyId']);
        /** @var UserFriendModel $friend */
        $friend = container()->get(UserFriendModel::class);
        $result = $friend->createFriend($applyResult['user_id'], $applyResult['apply_user_id']);
        //创建房间
        $this->sendToUser($request['user_id'], $this->sendMessage(SystemCode::SUCCESS, [], $request['message']));
        $this->sendToUser($request['apply_user_id'], $this->sendMessage(SystemCode::SUCCESS, [], $request['message']));
        mongoClient()->insert('user.room', ['user_id' => $userId, 'friend_id' => $applyResult['apply_user_id']]);
        mongoClient()->insert('user.room', ['user_id' => $applyResult['apply_user_id'], 'friend_id' => $userId]);
        return $this->success($result);
    }
}