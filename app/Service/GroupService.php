<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service;


use App\Constants\ApiCode;
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
     * @param $createUserId
     * @param $userIds
     * @return array
     */
    public function createGroup($createUserId, $userIds)
    {
        $groupName = Random::character(10);
        Db::beginTransaction();
        $createGroupResult = $this->groupModel->createGroup(['user_id' => $createUserId, 'group_name' => $groupName]);
        if(!$createGroupResult){
            Db::rollBack();
            return $this->fail(100);
        }
        return $this->success($createGroupResult);
    }

    /**
     * 更新群组信息
     * @param $param
     * @param $user_id
     * @return array
     */
    public function updateGroupInfo($param,$user_id)
    {

        if(!isset($param["id"])) return $this->fail(ApiCode::GROUP_NOT_EXIST);

        $group = $this->groupModel->getFind($param["id"]);

        if(is_array($group)) return $this->fail(ApiCode::GROUP_NOT_EXIST);

        $result = $this->groupModel->updateGroupInfo($param,$user_id);

        if(!$result){

            return $this->fail(ApiCode::OPERATION_FAIL);
        }

        return $this->success($result);
    }
}