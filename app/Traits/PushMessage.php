<?php

declare(strict_types=1);

namespace App\Traits;


use App\Model\UserModel;
use App\Service\Api\GroupService;
use App\WebSocket\Service\UserService;

trait PushMessage
{
    /**
     * 发送消息给指定用户
     * @param $data array 消息内容
     * @param $userId int 接收人
     * @param $senderId int 发送人
     * @return int
     */
    public function sendToUser(array $data, int $userId, int $senderId = 0): int
    {
        $count = 0;
        $fdInfo = $this->getUserFd($userId);
        if (!$fdInfo) {
            return $count;
        }
        $pushData = [
            'senderId' => $senderId,
            'userId' => $userId,
            'content' => $data
        ];
        return $this->push($fdInfo, $pushData);
    }

    /**
     * 发送消息给在线所有用户
     * @param array $data
     * @param int $senderId
     * @return int
     */
    public function sendToAll(array $data, int $senderId = 0): int
    {
        $count = 0;
        $userList = UserModel::query()->pluck('id');
        if ($userList === false || ($num = count($userList)) === 0) {
            return $count;
        }
        foreach ($userList as $userId) {
            if ($this->sendToUser($data, $userId, $senderId)) {
                $count++;
            };
        }
        return $count;
    }

    /**
     * 发送消息到群组
     * @param $data string|array 消息内容
     * @param $groupId int 群组id
     * @param $senderId int 发送人
     * @return int
     */
    public function sendToGroup($data, $groupId, $senderId = 0)
    {
        /** @var GroupService $group */
        $group = container()->get(GroupService::class);
        $groupMember = $group->getAllMember($groupId);
        $count = 0;
        foreach ($groupMember as $member) {
            $fdInfo = $this->getUserFd($member['id']);
            if (!$fdInfo) continue;
            $pushData = [
                'senderId' => $senderId,
                'groupId' => $groupId,
                'content' => $data
            ];
            if ($this->push($fdInfo, $pushData)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * 将数据推送到redis中
     * @param $fdInfo
     * @param $pushData
     * @return int
     */
    private function push($fdInfo, $pushData)
    {
        $count = 0;
        if (isOneArray($fdInfo)) {
            $pushData['fd'] = $fdInfo['fd'];
            redis()->publish($fdInfo['ip'], json_encode($pushData));
            $count++;
            return $count;
        }
        foreach ($fdInfo as $info) {
            $pushData['fd'] = $info['fd'];
            redis()->publish($info['ip'], json_encode($pushData));
            $count++;
        }
        return $count;
    }

    /**
     * 给指定用户发送信息
     * @param array $message
     * @param array $userIds
     * @param int $senderId
     * @return int
     */
    public function sendToSomeUser(array $message, array $userIds, int $senderId = 0)
    {
        $count = 0;
        foreach ($userIds as $userId) {
            if ($this->sendToUser($message, $userId, $senderId)) {
                $count++;
            }
        }
        return $count;
    }


    /**
     * 获取用户关联的fd
     * @param $userId
     * @return array|mixed|string
     */
    public function getUserFd($userId)
    {
        /** @var UserService $userService */
        $userService = container()->get(UserService::class);
        return $userService->getUserFd($userId);
    }
}