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
class StatusCode extends AbstractConstants
{
    /**
     * @HttpCode("200")
     * @Message("OK")
     */
    const OK = 200;

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
     * @HttpCode("401")
     * @Messager("Timestamp is required")
     */
    const EMPTY_TIMESTAMP = 10007;

    /**
     * @HttpCode("401")
     * @Message("Incorrect api key")
     */
    const INCORRECT_API_KEY = 10008;

    /**
     * @HttpCode("401")
     * @Message("Incorrect signature")
     */
    const INCORRECT_SIGNATURE = 10009;

    /**
     * @HttpCode("400")
     * @Message("Request is expired")
     */
    const REQUEST_EXPIRED = 10010;
}
