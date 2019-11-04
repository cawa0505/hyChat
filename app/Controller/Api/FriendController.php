<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 14:54
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Service\FriendService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FriendController
 * @package App\Controller\Api
 */
class FriendController extends AbstractController
{
    /**
     * @Inject()
     * @var FriendService
     */
    private $friendService;

    /**
     * 搜索用户
     * @return ResponseInterface
     */
    public function search()
    {
        $request = $this->request->getParsedBody();
        $rules = ['account' => 'required'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->error($errorMessage);
        }
        $result = $this->friendService->searchFriend($request['account']);
        return $this->success($result);
    }

    /**
     * 删除好友
     * @return ResponseInterface
     */
    public function delete()
    {
        $request = $this->request->getParsedBody();
        $rules = ['friendId' => 'required|numeric'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->error($errorMessage);
        }
        $userId = getContext('userId');
        $result = $this->friendService->deleteFriend($request['friendId'], $userId);
        return $this->success($result);
    }
}