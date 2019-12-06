<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service\Api;


use App\Constants\ApiCode;
use App\Model\UserGroupMember;
use App\Model\UserGroupModel;
use App\Service\BaseService;
use App\Utility\Random;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use MongoDB\Driver\Exception\Exception;

/**
 * Class GroupService
 * @package App\Service
 */
class GroupService extends BaseService
{
    /**
     * @Inject()
     * @var UserGroupModel
     */
    private $groupModel;
    /**
     * @Inject()
     * @var UserGroupMember
     */
    private $groupMember;


    /**
     * 创建群组
     * @param $createUserId
     * @param $userIds
     * @return array
     */
    public function createGroup($createUserId, $userIds)
    {
        //生成群组名称
//        $groupName = Random::character(10);
        $groupName="群聊";
        Db::beginTransaction();
        //获取创建群组ID
        $groupId = $this->groupModel->createGroup(['user_id' => $createUserId, 'group_name' => $groupName]);
        if (!$groupId) {
            Db::rollBack();
            return $this->fail(ApiCode::GROUP_CREATE_FAIL);
        }
        if (!is_array($userIds)) {
            Db::rollBack();
            return $this->fail(ApiCode::PARAMS_ERROR);
        }
        //构造群组成员初始化数据
        $groupMemberData = [];
        //添加自己
        $userIds[]=$createUserId;

        foreach ($userIds as $val) {

            $groupMemberData[] = ["group_id" => $groupId, "user_id" => $val, "status" => 1];//初始化成员不需要审核状态为正常

        }
        $createGroupUser = $this->groupMember->createData($groupMemberData);
        if (!$createGroupUser) {
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        Db::commit();
        return $this->success($createGroupUser);
    }

    /**
     * 更新群组信息
     * @param $param
     * @param $user_id
     * @return array
     */
    public function updateGroupInfo($param, $user_id)
    {
        if (!isset($param["id"])) {
            return $this->fail(ApiCode::GROUP_NOT_EXIST);
        }
        $group = $this->groupModel->getOne(["id" => $param["id"]]);
        if (!$group) {
            return $this->fail(ApiCode::GROUP_NOT_EXIST);
        }
        $result = $this->groupModel->updateGroupInfo($param, $user_id);
        if (!$result) {
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success($result);
    }

    /**
     * 解散群组
     * @param $id
     * @return array
     */
    public function deleteGroup($id)
    {
        $group = $this->groupModel->getOne(["id" => $id]);
        if (!$group) {
            return $this->fail(ApiCode::GROUP_NOT_EXIST);
        }
        Db::beginTransaction();
        //解散群成员
        $resultMember = $this->groupMember->deleteMember($id);
        if (!$resultMember) {
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        //删除群组
        $resultGroup = $this->groupModel->deleteGroup($id);
        if (!$resultGroup) {
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success([]);

    }

    /**
     * 加入群组
     * @param $groupId int 群组ID
     * @param $userId int 用户ID
     * @param $status int 状态0申请入群1邀请入群
     * @return array
     */
    public function joinMember($groupId, $userId, $status = 0)
    {
        $result = $this->groupMember->createData(["user_id" => $userId, "group_id" => $groupId, "status" => $status]);
        if (!$result) {

            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success($result);
    }

    /**
     * 更改成员群昵称
     * @param $param
     * @param $userId
     * @return array
     */
    public function updateNick($param, $userId)
    {
        $group = $this->groupModel->getOne(["id" => $param["id"]]);
        if (!$group) {
            return $this->fail(ApiCode::GROUP_NOT_EXIST);
        }
        $data = ["group_nick_name" => $param["group_nick_name"]];
        $where = [
            ["user_id" => $userId, "group_id" => $param["id"]]
        ];
        $result = $this->groupMember->updateMemberNick($data, $where);
        if (!$result) {
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success($result);
    }

    /**
     * 获取所有群组成员
     * @param $groupId
     * @return array
     */
    public function getAllMember($groupId)
    {
        $data = $this->groupMember->getAllMember($groupId);
        return $this->success($data);
    }

    /**
     * 获取消息
     * @param $request
     * @return array
     * @throws Exception
     */
    public function getMessageRecord($request)
    {
        $group = $request['groupId'];
        $limit = 10;
        $page = isset($request['page']) ? $request['page'] : 1;
        $skip = ($page - 1) * $limit;
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['create_time' => -1],
            'skip' => $skip,
            'limit' => $limit
        ];
        $result = mongoClient()->query('group.message', ['group' => $group], $options);
        return $this->success($result);
    }

    /**
     * 任命管理员
     * @param $param
     * @param $userId
     * @return array
     */
    public function appointAdmin($param, $userId)
    {
        //获取群信息
        $groupOwner = $this->groupModel->getOne(["id" => $param["groupId"]]);
        if (empty($groupOwner)) {
            return $this->fail(ApiCode::GROUP_NOT_EXIST);
        }
        //是不是群主
        if ($groupOwner["user_id"] != $userId) {
            return $this->fail(ApiCode::GROUP_APPOINT_NOT_ERROR);
        }
        //是不是群成员
        $memberInfo = $this->groupMember->getOne(["user_id" => $param["userId"], "group_id" => $param["groupId"]]);
        if (empty($memberInfo)) return $this->fail(ApiCode::GROUP_MEMBER_NOT_EXIST);
        //任命管理
        Db::beginTransaction();
        $where[] = ["id", "=", $memberInfo["id"]];
        $data = ["is_admin" => 1];
        $resultISAdmin = $this->groupMember->updateField($where, $data);
        if (!$resultISAdmin) {
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        //更新管理员人数
        $where[] = ["id", "=", $param["groupId"]];
        $data = ["administrator_num" => $groupOwner["administrator_num"]];
        $resultAdminNum = $this->groupModel->updateField($where, $data);
        if (!$resultAdminNum) {
            Db::rollBack();
            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        Db::commit();
        return $this->success([]);
    }
}