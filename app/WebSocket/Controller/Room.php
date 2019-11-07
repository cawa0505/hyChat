<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/11
 * Time: 17:43
 */

namespace App\WebSocket\Controller;

use App\Service\RoomService;
use App\WebSocket\Common;
use Hyperf\Di\Annotation\Inject;

/**
 * 单人|私聊房间
 * Class Room
 * @package App\WebSocket\Controller
 */
class Room extends Common
{
    /**
     * @Inject()
     * @var RoomService
     */
    protected $roomService;

    /**
     * {"controller":"Room","action":"create","content":{"userId":"1","message":"123456"}}
     */
    public function create()
    {

    }
}