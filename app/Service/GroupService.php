<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service;


use App\Constants\ApiCode;
use App\Model\UserGroupMember;
use App\Model\UserGroupModel;
use App\Utility\Random;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

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
        $groupName = Random::character(10);
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
        foreach ($userIds as $val) {
            $groupMember[] = ["group_id" => $groupId, "user_id" => $val];
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
        if (!isset($param["id"])) return $this->fail(ApiCode::GROUP_NOT_EXIST);

        $group = $this->groupModel->getOne(["id" => $param["id"]]);

        if (is_array($group)) return $this->fail(ApiCode::GROUP_NOT_EXIST);

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

        if (!is_array($group)) return $this->fail(ApiCode::GROUP_NOT_EXIST);

        Db::beginTransaction();

        $resultGroup = $this->groupModel->deleteGroup($id);

        if (!$resultGroup) {

            Db::rollBack();

            return $this->fail(ApiCode::OPERATION_FAIL);
        }
        //解散群成员
        $resultMember = $this->groupMember->deleteMember($id);

        if (!$resultMember) {

            Db::rollBack();

            return $this->fail(ApiCode::OPERATION_FAIL);
        }

        return $this->success([]);

    }

    /**
     *
     * @param $groupId
     * @param $userId
     * @return array
     */
    public function joinMember($groupId, $userId)
    {
        $result = $this->groupMember->createData(["user_id" => $userId, "group_id" => $groupId]);

        return $this->success($result);
    }
}