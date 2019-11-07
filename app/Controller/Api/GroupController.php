<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:43
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Request\GroupRequest;
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
     * @param GroupRequest $request
     * @return ResponseInterface
     */
    public function create(GroupRequest $request)
    {
        $result = $this->groupService->createGroup($this->getUserId(),$request->input('userIds'));
        return $this->successResponse($result);
    }

    /**
     * 更新群组信息
     */
    public function update()
    {

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