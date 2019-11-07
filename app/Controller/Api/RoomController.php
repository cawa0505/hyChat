<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:04
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Request\RoomRequest;
use App\Service\RoomService;
use Hyperf\Di\Annotation\Inject;
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
        $result = $this->roomService->createRoom($this->getUserId(), $request->input('friendId'));
        return $this->successResponse($result);
    }

    /**
     * 删除房间
     * @param RoomRequest $request
     * @return ResponseInterface
     */
    public function delete(RoomRequest $request)
    {
        $result = $this->roomService->deleteRoom($this->getUserId(), $request->input('friendId'));
        return $this->successResponse($result);
    }
}