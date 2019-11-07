<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 12:24
 */

namespace App\Service;


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
    }
}