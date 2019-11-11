<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/8
 * Time: 9:49
 */

namespace App\WebSocket\Service;


/**
 * 绑定用户
 * Class UserService
 * @package App\WebSocket\Service
 */
class UserService
{
    /**
     * 设置user关联的fd
     * @param $userId int
     * @param $fd int
     * @param $loginMethod int 设配端 1 App 2 Web
     * @return bool|int
     */
    public function setUserFd($userId, $fd, $loginMethod = 0)
    {
        return redis()->hSet('userFd_' . $loginMethod, (string)$userId, $fd);
    }

    /**
     * 获取user的关联的fd
     * @param $userId int
     * @param $loginMethod int 设配端 1 App 2 Web
     * @return array|mixed|string
     */
    public function getUserFd($userId, $loginMethod = 0)
    {
        if (!$loginMethod) {
            // 获取app端绑定的userId绑定的fd信息
            $userFdApp = redis()->hGet('userFd_' . 1, (string)$userId);
            // 获取web端绑定的userId绑定的fd信息
            $userFdWeb = redis()->hGet('userFd_' . 2, (string)$userId);
            if (!$userFdApp) {
                return json_decode($userFdWeb, true);
            }
            if (!$userFdWeb) {
                return json_decode($userFdApp, true);
            }
            return array_merge(json_decode($userFdWeb, true), json_decode($userFdApp, true));
        }
        $fdInfo = redis()->hGet('userFd_' . $loginMethod, (string)$userId);
        return json_decode($fdInfo, true);
    }

    /**
     * @param $userId int
     * @param $loginMethod int
     * @return bool|int
     */
    public function deleteUserFd($userId, $loginMethod = 0)
    {
        return redis()->hDel('userFd_' . $loginMethod, (string)$userId);
    }
}