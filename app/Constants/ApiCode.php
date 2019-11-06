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
class ApiCode extends AbstractConstants
{
    // TODO ----------------------通用验证100-500-------------------------
    /**
     * @HttpCode("1")
     * @Message("参数错误")
     */
    const PARAMS_ERROR = 1;

    // TODO ----------------------用户 1000-1500-------------------------
    /**
     * @HttpCode("1001")
     * @Message("验证码错误")
     */
    const AUTH_CODE_ERROR = 1001;
    /**
     * @HttpCode("1002")
     * @Message("验证码不匹配")
     */
    const AUTH_CODE_NOT_EXIST = 1002;
    /**
     * @HttpCode("1003")
     * @Message("此用户已存在")
     */
    const AUTH_USER_EXIST = 1003;
    /**
     * @HttpCode("1005")
     * @Message("注册失败")
     */
    const AUTH_REGISTER_ERR = 1005;

    /**
     * @HttpCode("1020")
     * @Message("未登录")
     */
    const NOT_LOGIN = 1020;
    /**
     * @HttpCode("1021")
     * @Message("重新登录")
     */
    const RENEW_LOGIN = 1021;

    // TODO ----------------------申请状态码 2000-2200-------------------------

    /**
     * @HttpCode("2000")
     * @Message("不能添加自己为好友")
     */
    const CANT_ADD_SELF = 2000;
}
