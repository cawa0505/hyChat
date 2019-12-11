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
class SystemCode extends AbstractConstants
{
    /**
     * @HttpCode("200")
     * @Message("success")
     */
    const SUCCESS = 200;

    /**
     * @HttpCode("404")
     * @Message("Not Found")
     */
    const NOT_FOUND = 404;

    /**
     * @HttpCode("405")
     * @Message("Method Not Allowed")
     */
    const METHOD_NOT_ALLOWED = 405;

    /**
     * @HttpCode("422")
     * @Message("Unprocessable Entity")
     */
    const UNPROCESSABLE_ENTITY = 422;

    /**
     * @HttpCode("500")
     * @Message("Internal Server Error")
     */
    const SERVER_ERROR = 500;

    /**
     * @HttpCode("400")
     * @Message("Incorrect password")
     */
    const INCORRECT_PASSWORD = 10001;

    /**
     * @HttpCode("400")
     * @Message("Token is required")
     */
    const EMPTY_TOKEN = 10002;

    /**
     * @HttpCode("400")
     * @Message("User not Found")
     */
    const USER_NOT_FOUND = 10003;

    /**
     * @HttpCode("401")
     * @Message("Incorrect token")
     */
    const INCORRECT_TOKEN = 10004;

    /**
     * @HttpCode("401")
     * @Messager("Api key is required")
     */
    const EMPTY_API_KEY = 10005;

    /**
     * @HttpCode("401")
     * @Messager("Signature is required")
     */
    const EMPTY_SIGNATURE = 10006;

    /**
     * @HttpCode("400")
     * @Message("Request is expired")
     */
    const REQUEST_EXPIRED = 10010;

    // TODO ------------------------ rsa解密---------------------------
    /**
     * @HttpCode("10050")
     * @Message("encrypt不能为空")
     */
    const ENCRYPT_NOT_EMPTY = 10050;

    /**
     * @HttpCode("10051")
     * @Message("解析失败")
     */
    const ENCRYPT_DECODE_ERROR = 10051;
    /**
     * @HttpCode("10052")
     * @Message("sign不能为空")
     */
    const ENCRYPT_SIGN_EMPTY = 10052;

    /**
     * @HttpCode("10053")
     * @Message("签名错误")
     */
    const ENCRYPT_SIGN_ERROR = 10053;
}
