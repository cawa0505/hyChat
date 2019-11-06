<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 17:43
 */

namespace App\WebSocket\Controller;

use App\Model\UserFriendModel;
use App\Service\RoomService;
use App\WebSocket\Service\CommonServer;
use Hyperf\Di\Annotation\Inject;

/**
 * 单人|私聊房间
 * Class Room
 * @package App\WebSocket\Controller
 */
class Room extends CommonServer
{
    /**
     * @Inject()
     * @var RoomService
     */
    protected $roomService;

    /**
     * {"class":"Room","action":"create","content":{"userId":"1","message":"123456"}}
     */
    public function create()
    {
        $sendData = $this->getData();
        //获取发送者userId
        $sendUserId = $this->getFdUser($this->getFd());
        //获取接收者userId
        $receiveUserId = $sendData['userId'];
        //消息
        $message = $sendData['message'];

        if ($sendUserId == $receiveUserId) {
            $this->push($this->getFd(), "不能向自己发送消息");
            return;
        }
        // 获取接收者UserId关联的fd
        $receiveUserFd = $this->getUserFd($sendData['userId']);
        /** @var UserFriendModel $userFriendModel */
        $userFriendModel = $this->container->get(UserFriendModel::class);
        // 判断接收者好友中是否有发送者
        $friendResult = $userFriendModel->getFriendIdsByUserId($receiveUserId);
        if (!in_array($sendUserId, $friendResult)) {
            $this->push($this->getFd(), "您并非对方好友");
            return;
        }
        // 给接收人创建房间
        $this->roomService->createRoom($receiveUserId, $sendUserId);
        $messageContent = [
            'sendUserId' => $sendUserId,
            'receiveUserId' => $receiveUserId,
            'message' => $message,
        ];
        dd($messageContent);
        // 判断接收者是否在线 在线则发送消息通知
        if ($this->exist($receiveUserFd)) {
            dd($receiveUserFd);
        }
    }
}