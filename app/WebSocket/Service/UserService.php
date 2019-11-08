<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/8
 * Time: 9:49
 */

namespace App\WebSocket\Service;


class UserService
{
    // TODO -------------------绑定用户-------------------------------

    /**
     * 设置user关联的fd
     * @param $userId int
     * @param $fd int
     * @param $login_type int 设配端 1 App 2 Web
     * @return bool|int
     */
    public function setUserFd($userId, $fd, $login_type = 0)
    {
        return redis()->hSet('userFd_' . $login_type, (string)$userId, $fd);
    }

    /**
     * 获取user的关联的fd
     * @param $userId int
     * @param $login_type int 设配端 1 App 2 Web
     * @return array|mixed|string
     */
    public function getUserFd($userId, $login_type = 0)
    {
        if (!$login_type) {
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
        $fdInfo = redis()->hGet('userFd_' . $login_type, (string)$userId);
        return json_decode($fdInfo, true);
    }

    /**
     * @param $userId int
     * @param $login_type int
     * @return bool|int
     */
    public function deleteUserFd($userId, $login_type = 0)
    {
        return redis()->hDel('userFd_' . $login_type, (string)$userId);
    }
}