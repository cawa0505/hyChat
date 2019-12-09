<?php

declare(strict_types=1);

namespace App\Traits;


use App\Service\Api\GroupService;
use App\WebSocket\Service\UserService;

trait PushMessage
{
    /**
     * @param $userId
     * @param $data
     * @return int
     */
    public function sendToUser($userId, $data): int
    {
        $count = 0;
        /** @var UserService $userService */
        $userService = container()->get(UserService::class);
        $fdInfo = $userService->getUserFd($userId);
        if (!$fdInfo) return $count;
        if (isOneArray($fdInfo)) {
            $pushData = [
                'fd' => $fdInfo['fd'],
                'data' => $data
            ];
            redis()->publish($fdInfo['ip'], json_encode($pushData));
            $count++;
            return $count;
        }
        foreach ($fdInfo as $info) {
            $pushData = [
                'fd' => $info['fd'],
                'data' => $data
            ];
            redis()->publish($info['ip'], json_encode($pushData));
            $count++;
        }
        return $count;
    }

    /**
     * 发送消息到群组
     * @param $groupId
     * @param $data
     */
    public function sendToGroup($groupId, $data)
    {
        /** @var GroupService $groupMember */
        $groupMember = container()->get(GroupService::class);
        $groupMember->getAllMember($groupId);
        foreach ($groupMember as $member) {
            $this->sendToUser($member['user_id'], $data);
        }
    }
}