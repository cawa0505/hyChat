<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:06
 */

namespace App\Service;

use App\Constants\SocketCode;
use App\Constants\SystemCode;
use App\Model\UserApplyModel;
use App\WebSocket\Common;
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
            return $this->fail("不能添加自己为好友");
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
        /** 实例化socketCommon对象 @var Common $socketCommon */
        $socketCommon = $this->container->get(Common::class);
        $userFd = $socketCommon->getUserFd($request['friendId']);
        // 发送申请提醒
        $socketCommon->sendTo($userFd, $this->sendMessage(SystemCode::OK, [], '好友申请添加提醒'));
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
    public function review($request, $userId)
    {
        // status 1 通过 2 拒绝
        if ($request['status'] == 2) {
            echo $userId;
        }
        return $this->success();
    }
}