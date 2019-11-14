<?php

namespace App\Traits;

use App\Constants\ApiCode;
use App\Constants\MessageCode;
use App\Constants\SystemCode;

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
    public function success($data=[], $code = 200)
    {

        if (is_string($data)) {
            $count = 1;
        } else {
            $count = count($data);
            if ($count == count($data, 1) && $count) {
                $count = 1;
            }
        }

        return [
            'code' => $code,
            'result' =>
                [
                    'count' => $count,
                    'data' => $data
                ]
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

        if (is_null($message)) {
            $message = ApiCode::getMessage($code) ??"";
            if(!$message){
                $message = SystemCode::getMessage($code)??"未知错误";
            }
        }
        return [
            'code' => $code,
            'message' => $message
        ];
    }

    /**
     * 推送消息
     * @param $code int 类型
     * @param $data array 数据
     * @return string
     */
    public function sendMessage($code,array $data = []): string
    {
        $data = [
            'code' => $code,
            'message' => MessageCode::getMessage($code),
            'data' => $data
        ];
        return json_encode($data);
    }
}
