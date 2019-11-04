<?php

namespace App\Traits;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait Response
{
    /**
     * 拼装成功数据
     * @param array $data
     * @param int $code
     * @return array
     */
    public function success($data = [], $code = 200)
    {
        $count = count($data);
        if ($count == count($data, 1) && $count) {
            $count = 1;
        }
        return [
            'code' => $code,
            'result' => [
                'count' => $count,
                'data' => $data
            ]
        ];
    }

    /**
     * 异常返回
     * @param int $code
     * @param string $message
     * @return array
     */
    public function fail($message = 'error', $code = 100)
    {
        return [
            'code' => $code,
            'message' => $message
        ];
    }

    /**
     * 组装向前端推送消息
     * @param $code int 类型
     * @param $data array 数据
     * @param $message string 消息提示
     * @return string
     */
    public function sendMessage($code, $data = [], $message = ''): string
    {
        $data = [
            'code' => $code,
            'result' => [
                'data' => $data,
                'message' => $message,
            ]
        ];
        return json_encode($data);
    }
}
