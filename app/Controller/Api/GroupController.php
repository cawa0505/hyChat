<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:43
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Model\UserGroupModel;
use App\Request\GroupRequest;
use App\Service\GroupService;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Di\Annotation\Inject;
use MongoDB\Driver\Exception\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GroupController
 * @package App\Controller\Api
 */
class GroupController extends AbstractController
{
    /**
     * @Inject()
     * @var GroupService
     */
    private $groupService;

    /**
     * 好友列表
     * @return ResponseInterface
     */
    public function list()
    {
        /** @var UserGroupModel $userGroup */
        $userGroup = $this->container->get(UserGroupModel::class);
        $result = $userGroup->getGroupByUserId($this->getUserId());
        return $this->successResponse($this->success($result));
    }

    /**
     * 创建群组
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function create(GroupRequest $request)
    {
        $result = $this->groupService->createGroup($this->getUserId(), $request->post("userIds"));
        return $this->successResponse($result);
    }

    /**
     * 更新群组信息
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function update(GroupRequest $request)
    {
        $result = $this->groupService->updateGroupInfo($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 解散群组
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function delete(GroupRequest $request)
    {
        $groupResult = $this->groupService->deleteGroup($request->post("id"));
        return $this->successResponse($groupResult);
    }

    /**
     * 加入申请
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function join(GroupRequest $request)
    {
        $result = $this->groupService->joinMember($request->post("id"), $this->getUserId());

        return $this->successResponse($result);
    }

    /**
     * 邀请入群
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function invite(GroupRequest $request)
    {
        $result = $this->groupService->joinMember($request->post("id"), $this->getUserId(), 1);
        return $this->successResponse($result);

    }

    /**
     * 编辑群昵称
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function updateNick(GroupRequest $request)
    {
        $result = $this->groupService->updateNick($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 获取所有群组成员
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function memberList(GroupRequest $request)
    {
        $result = $this->groupService->getAllMember($request->post("id"));
        return $this->successResponse($result);
    }

    /**
     * 获取群聊纪录
     * @throws Exception
     */
    public function messageRecord()
    {
        $request = $this->request->all();
        $result = $this->groupService->getMessageRecord($request);
        return $this->successResponse($result);
    }
}