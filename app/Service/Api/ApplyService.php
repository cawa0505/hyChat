<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:06
 */

namespace App\Service\Api;

use App\Constants\ApiCode;
use App\Constants\MessageCode;
use App\Model\UserApplyModel;
use App\Model\UserFriendModel;
use App\Model\UserModel;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use MongoDB\Driver\Exception\Exception;

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
            'friend_id' => $request['friendId'],
            'user_id' => $userId,
            'create_time' => time()
        ];
        if (isset($request['message']) && $request['message']) {
            $data['message'] = $request['message'];
        }
        // 创建申请记录
        $result = $this->userApplyModel->create($data);
        $userInfo = $this->userModel->getUserByUserId($userId, ['nick_name']);
        // 发送申请提醒
        $this->sendToUser(
            $request['friendId'],
            $this->sendMessage(MessageCode::ADD_APPLY, [], sprintf("{$userInfo['nick_name']},请求添加你为好友"))
        );
        return $this->success($result);
    }

    /**
     * 通过用户id获取申请
     * @param $userId
     * @return array
     */
    public function getApplyByUserId($userId)
    {
        $applyResult = $this->userApplyModel->getApplyByUserId($userId, ['id as apply_id', 'user_id', 'message', 'status']);
        if (!$applyResult) {
            return $this->success();
        }
        $applyUserId = array_column($applyResult, 'user_id');
        $applyUserIdInfo = $this->userModel->getUserByUserIds($applyUserId, ['id', 'nick_name', 'image_url']);
        $result = [];
        foreach ($applyResult as $key => $item) {
            foreach ($applyUserIdInfo as $k => $v) {
                if ($item['user_id'] == $v['id']) {
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
     * @throws Exception
     */
    public function reviewApply($request, $userId)
    {
        // 获取审核信息
        $applyResult = $this->userApplyModel->getApplyById($request['applyId']);
        if (!$applyResult) {
            return $this->fail(ApiCode::APPLY_RECORDS_NOT_FOUND);
        }
        // TODO status 1 通过 2 拒绝
        if ($request['status'] == 2) {
            // 记录回复信息
            mongoClient()->insert('user.apply', ['user_id' => $userId, 'friend_id' => $applyResult['friend_id']]);
            $userInfo = $this->userModel->getUserByUserId($userId, ['nick_name']);
            // 给发送人推送消息
            $this->sendToUser(
                $request['friendId'],
                $this->sendMessage(MessageCode::ADD_APPLY, [], sprintf("{$userInfo['nick_name']},请求添加你为好友"))
            );
            return $this->success();
        }
        $createData = [
            'user_id' => $applyResult['user_id'],
            'friend_id' => $applyResult['friend_id']
        ];
        // 查看关系是否存在
        $friendResult = $this->userFriendModel->getMany($createData);
        if ($friendResult) {
            Db::rollBack();
            return $this->fail(ApiCode::FRIEND_EXITS);
        }
        Db::beginTransaction();
        // 创建双方关系
        $createFriend = $this->userFriendModel->createFriend(['user_id' => $applyResult['user_id'], 'friend_id' => $applyResult['friend_id']]);
        $result = $this->userFriendModel->createFriend(['user_id' => $applyResult['friend_id'], 'friend_id' => $applyResult['user_id']]);
        if (!$result || !$createFriend) {
            Db::rollBack();
            return $this->fail(ApiCode::CREATE_FRIEND_ERROR);
        }
        // 修改审核记录为已审核
        $updateResult = $this->userApplyModel->updateData($applyResult['id'], ['status' => 1, 'update_time' => time()]);
        if (!$updateResult) {
            Db::rollBack();
            return $this->fail(ApiCode::APPLY_ERROR);
        }
        Db::commit();
        //创建房间
        $messageData = mongoClient()->query('user.room', ['user_id' => $userId, 'friend_id' => $applyResult['friend_id']]);
        $this->sendToUser($applyResult['friend_id'], $this->sendMessage(MessageCode::ADD_AGREE, $messageData));
        $this->sendToUser($applyResult['user_id'], $this->sendMessage(MessageCode::ADD_AGREE, $messageData));
        mongoClient()->insert('user.room', ['user_id' => $userId, 'friend_id' => $applyResult['friend_id']]);
        mongoClient()->insert('user.room', ['user_id' => $applyResult['friend_id'], 'friend_id' => $userId]);
        return $this->success($result);
    }
}