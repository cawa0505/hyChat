<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/12
 * Time: 10:04
 */

namespace App\WebSocket\Controller;


/**
 * 群组聊天
 * Class Group
 * @package App\WebSocket\Controller
 */
class GroupController extends BaseController
{
    /**
     * {"controller":"Group","action":"send","content":{"groupId":"1","message":"123456"}}
     */
    public function send()
    {
        $data = $this->getData();
        $this->sendToGroup($data['groupId'], $data['message']);
        $senderId = $this->getUid();
        go(function () use ($senderId, $data) {
            mongoClient()->insert('Group.message',
                [
                    'group' => $data['groupId'],
                    'sender' => $senderId,
                    'message' => $data['message']
                ]
            );
        });
    }
}