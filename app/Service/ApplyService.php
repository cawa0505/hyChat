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
use App\WebSocket\Service\SocketServer;
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
        /** @var SocketServer $socketCommon */
        $socketCommon = container()->get(SocketServer::class);
        // 发送申请提醒
        $socketCommon->sendToUser($request['friendId'], $this->sendMessage(SystemCode::SUCCESS));
        return $this->success($result);
    }

    /**
     * 通过用户id获取申请
     * @param $userId
     * @return array
     */
    public function getApplyByUserId($userId)
    {
        $result = $this->userApplyModel->getApplyByUserId($userId);
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
        mongoClient()->insert('user.room', ['user_id' => $userId, 'friend_id' => $applyResult['apply_user_id']]);
        mongoClient()->insert('user.room', ['user_id' => $applyResult['apply_user_id'], 'friend_id' => $userId]);
        return $this->success($result);
    }
}