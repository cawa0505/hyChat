<?php

declare(strict_types=1);

namespace App\Constants;


use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 *
 * @method static getMessage(int $code)
 * @method static getHttpCode(int $code)
 */
class MessageCode extends AbstractConstants
{
    // TODO ---------------------------系统消息(100-120)----------------------------------

    /**
     * @HttpCode("101")
     * @Message("连接成功")
     */
    const CONNECT = 101;

    /**
     * @HttpCode("102")
     * @Message("退出")
     */
    const LOGOUT = 102;

    /**
     * @HttpCode("103")
     * @Message("大厅消息")
     */
    const HALL = 103;

    // TODO ---------------------------好友消息(121-140)----------------------------------

    /**
     * @HttpCode("121")
     * @Message("添加申请")
     */
    const ADD_APPLY = 121;

    /**
     * @HttpCode("122")
     * @Message("添加回复")
     */
    const ADD_REPLY = 122;

    /**
     * @HttpCode("123")
     * @Message("添加同意")
     */
    const ADD_AGREE = 123;

    /**
     * @HttpCode("124")
     * @Message("一对一聊天")
     */
    const ROOM_CHAT = 124;

    /**
     * @HttpCode("125")
     * @Message("不是对方好友")
     */
    const NO_OTHER_FRIEND = 125;

    // TODO ---------------------------群组消息(141-160)----------------------------------

    /**
     * @HttpCode("141")
     * @Message("创建群组")
     */
    const CREATE_GROUP = 141;

    /**
     * @HttpCode("142")
     * @Message("更新群组信息")
     */
    const UPDATE_GROUP = 142;

    /**
     * @HttpCode("143")
     * @Message("加入群组")
     */
    const JOIN_GROUP = 143;

    /**
     * @HttpCode("144")
     * @Message("审核加入群组")
     */
    const REVIEW_JOIN_GROUP = 144;

    /**
     * @HttpCode("145")
     * @Message("邀请入群")
     */
    const INVITE_JOIN_GROUP = 145;

    /**
     * @HttpCode("146")
     * @Message("群聊")
     */
    const GROUP_CHAT = 146;
}