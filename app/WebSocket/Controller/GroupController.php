<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/12
 * Time: 10:04
 */

declare(strict_types=1);

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
        $this->sendToGroup($this->getUid(),$data['groupId'], $data['message']);
    }
}