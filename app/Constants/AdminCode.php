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
     * @HttpCode("200")
     * @Message("操作成功")
     */
    const SUCCESS = 200;
}