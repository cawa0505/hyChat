<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:04
 */
declare(strict_types=1);

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Request\Api\RoomRequest;
use App\Service\Api\RoomService;
use Hyperf\Di\Annotation\Inject;
use MongoDB\Driver\Exception\Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RoomController
 * @package App\Controller\Api
 */
class RoomController extends AbstractController
{
    /**
     * @Inject()
     * @var RoomService
     */
    private $roomService;

    /**
     * 创建房间
     * @param RoomRequest $request
     * @return ResponseInterface
     */
    public function create(RoomRequest $request)
    {
        $result = $this->roomService->createRoom($this->getUserId(), $request->post('friendId'));
        return $this->successResponse($result);
    }

    /**
     * 聊天记录
     * @return ResponseInterface
     * @throws Exception
     */
    public function messageRecord()
    {
        $request = $this->request->all();
        $result = $this->roomService->getMessageRecord($request);
        return $this->successResponse($result);
    }

    /**
     * 删除房间
     * @param RoomRequest $request
     * @return ResponseInterface
     */
    public function delete(RoomRequest $request)
    {
        $result = $this->roomService->deleteRoom($this->getUserId(), $request->post('friendId'));
        return $this->successResponse($result);
    }
}