<?php


namespace App\Constants;


use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 *
 * @method static getMessage(int $code)
 * @method static getHttpCode(int $code)
 */
class AdminCode extends AbstractConstants
{
    // TODO ----------------------通用验证100-500-------------------------
    /**
     * @HttpCode("101")
     * @Message("操作失败")
     */
    const ERROR = 100;

    /**
     * @HttpCode("101")
     * @Message("用户未找到")
     */
    const USER_NOT_FOUND = 101;

    /**
     * @HttpCode("102")
     * @Message("密码错误")
     */
    const USER_PASSWORD_ERROR = 102;

    /**
     * @HttpCode("103")
     * @Message("密码错误")
     */
    const USER_DISABLE = 103;

    /**
     * @HttpCode("200")
     * @Message("操作成功")
     */
    const SUCCESS = 200;

}