<?php

namespace App\Traits;

use App\Constants\AdminCode;
use App\Constants\ApiCode;
use App\Constants\MessageCode;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait Response
{
    /**
     * 拼装成功数据
     * @param $data
     * @param int $code
     * @return array
     */
    public function success($data = [], $code = 200)
    {
        return [
            'code' => $code,
            'result' => $data
        ];
    }

    /**
     * 异常返回
     * @param int $code
     * @param null $message
     * @return array
     */
    public function fail(int $code = 100, $message = null)
    {
        if (!is_null($message)) {
            return [
                'code' => $code,
                'message' => $message
            ];
        }
        $callClass = get_called_class();
        if (strpos($callClass, 'Admin') !== false) {
            $message = AdminCode::getMessage($code) ?? "";
        } else {
            $message = ApiCode::getMessage($code) ?? "";
        }
        return [
            'code' => $code,
            'message' => $message
        ];
    }

    /**
     * 推送消息
     * @param $code
     * @param array $data
     * @param string $message
     * @return array
     */
    public function sendMessage($code, array $data = [], $message = ''): array
    {
        return [
            'code' => $code,
            'message' => $message ? $message : MessageCode::getMessage($code),
            'data' => $data
        ];
    }
}
