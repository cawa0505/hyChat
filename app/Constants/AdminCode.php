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
class AdminCode extends AbstractConstants
{
    // TODO ----------------------通用验证100-500-------------------------
    /**
     * @HttpCode("101")
     * @Message("操作失败")
     */
    const ERROR = 100;


    /**
     * @HttpCode("200")
     * @Message("操作成功")
     */
    const SUCCESS = 200;

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
     * @HttpCode("150")
     * @Message("已存在")
     */
    const ALREADY_EXISTS = 150;


    /**
     * @HttpCode("151")
     * @Message("添加失败")
     */
    const CREATE_ERROR = 151;

    /**
     * @HttpCode("152")
     * @Message("添加失败")
     */
    const UPDATE_ERROR = 152;

    /**
     * @HttpCode("153")
     * @Message("删除失败")
     */
    const DELETE_ERROR = 153;
}