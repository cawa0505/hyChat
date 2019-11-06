<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 18:04
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
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
     * @return ResponseInterface
     */
    public function create()
    {
        $request = $this->request->getParsedBody();
        $rules = ['friendId' => 'required'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->errorResponse($errorMessage);
        }
        $userId = getContext("userId");
        $result = $this->roomService->createRoom($userId, $request['friendId']);
        return $this->successResponse($result);
    }

    /**
     * 删除房间
     * @return ResponseInterface
     */
    public function delete()
    {
        $request = $this->request->getParsedBody();
        $rules = ['friendId' => 'required'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->errorResponse($errorMessage);
        }
        $userId = getContext("userId");
        $result = $this->roomService->deleteRoom($userId, $request['friendId']);
        return $this->successResponse($result);
    }
}