<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/12
 * Time: 10:04
 */

namespace App\WebSocket\Controller;


use App\WebSocket\Service\CommonServer;

/**
 * 群组聊天
 * Class Group
 * @package App\WebSocket\Controller
 */
class Group extends CommonServer
{
    /**
     * {"class":"Index","action":"index","content":{"userId":"1","message":"123456"}}
     */
    public function send()
    {

    }
}