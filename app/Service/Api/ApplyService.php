<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:06
 */

declare(strict_types=1);

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
        Db::beginTransaction();
        $result = $this->userApplyModel->createUserApply($data);
        if(!$result){
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }

        //创建单方关系申请
        $userFriend=$this->userFriendModel->getOne(['user_id' => $userId, 'friend_id' => $request['friendId']]);
        if(!$userFriend) {
            $resultOneRelation=$this->userFriendModel->createFriend(['user_id' => $userId, 'friend_id' => $request['friendId']]);
            if (!$resultOneRelation){
                Db::rollBack();
                return $this->fail(ApiCode::OPERATION_FAIL);
            }
        }
        $userInfo = $this->userModel->getUserByUserId($userId, ['nick_name']);
        // 发送申请提醒
        $this->sendToUser($request['friendId'],
            $this->sendMessage(MessageCode::ADD_APPLY, [], sprintf("{$userInfo['nick_name']},请求添加你为好友"))
        );
        Db::commit();
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
        // 获取我的好友
        $userFriend = $this->userFriendModel->getFriendIdsByUserId($userId, ['friend_id']);
        $friendIds = array_column($userFriend, 'friend_id');
        //申请列表用户ID
        $applyUserId = array_column($applyResult, 'user_id');
        $applyUserIdInfo = $this->userModel->getUserByUserIds($applyUserId, ['id', 'nick_name', 'image_url']);
        $result = [];
        foreach ($applyResult as $key => $item) {
            if (in_array($item['user_id'],$friendIds)){
                $item["status"]=1;
            }
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
        Db::beginTransaction();
        if ($request['status'] == 2) {
            //获取好友申请
            $relationData=$this->userFriendModel->getOne(["friend_id"=> $applyResult['friend_id'],'user_id' => $userId]);
            if (!$relationData){
                Db::rollBack();
                return $this->fail(ApiCode::OPERATION_FAIL);
            }
            $this->userFriendModel->updateFriend(["friend_id"=> $applyResult['friend_id'],'user_id' => $userId],["status"=>$request['status']]);
            $userInfo = $this->userModel->getUserByUserId($userId, ['nick_name']);
            // 给发送人推送消息
            $this->sendToUser($request['friendId'],
                $this->sendMessage(MessageCode::ADD_APPLY, [], sprintf("{$userInfo['nick_name']},请求添加你为好友"))
            );
            Db::commit();
            return $this->success();
        }
        $createData = [
            'user_id' => $applyResult['user_id'],
            'friend_id' => $applyResult['friend_id']
        ];
        // 查看关系是否存在
        $friendResult = $this->userFriendModel->getOne($createData);

        if (!$friendResult) {
            return $this->fail(ApiCode::FRIEND_EXITS);
        }
        // 创建双方关系
        $createFriend = $this->userFriendModel->updateFriendName(["friend_id"=> $applyResult['friend_id'],'user_id' => $userId],["status"=>$request['status']]);
        $result = $this->userFriendModel->createFriend(['user_id' => $applyResult['friend_id'], 'friend_id' => $applyResult['user_id'],"status"=>1]);
        if (!$result || !$createFriend) {
            Db::rollBack();
            return $this->fail(ApiCode::CREATE_FRIEND_ERROR);
        }
        // 修改审核记录为已审核
        $updateResult = $this->userApplyModel->updateUserApply($applyResult['id'], ['status' => 1, 'update_time' => time()]);
        if (!$updateResult) {
            Db::rollBack();
            return $this->fail(ApiCode::APPLY_ERROR);
        }
        Db::commit();
        return $this->success($result);
    }
}