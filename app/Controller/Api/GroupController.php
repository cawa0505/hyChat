<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:43
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Request\Group\CreateRequest;
use App\Service\GroupService;
use Hyperf\Di\Annotation\Inject;
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
     * 创建群组
     * @param CreateRequest $request
     * @return ResponseInterface
     */
    public function create(CreateRequest $request)
    {
        $result = $this->groupService->createGroup($this->getUserId(),$request->input('userIds'));
        return $this->successResponse($result);
    }

    /**
     * 更新群组信息
     */
    public function update()
    {
        $param=$this->request->all();
        $user_id=$this->getUserId();
        $result=$this->groupService->updateGroupInfo($param,$user_id);
        return $this->successResponse($result);
    }

    /**
     * 删除群组
     */
    public function delete()
    {

    }

    /**
     * 加入申请
     */
    public function join()
    {

    }
}