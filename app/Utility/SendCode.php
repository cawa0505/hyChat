<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/10
 * Time: 16:49
 */

namespace App\Utility;

use App\Traits\Request;
use GuzzleHttp\Exception\RequestException;

/**
 * 发送验证码
 * Class SendCode
 * @package App\Utility
 */
class SendCode
{
    use Request;

    /**
     * 获取短信验证码
     * @param $mobile
     * @return array
     */
    public function send($mobile)
    {
        $verifyCode = mt_rand(100000, 999999);
        $data = [
            'apikey' => env("API_KEY"),
            'text' => $this->sendVerifyTemplate($verifyCode),
            'mobile' => $mobile,
        ];
        try {
            $result = $this->requestPost("https://sms.yunpian.com/", 'v2/sms/single_send.json', $data);
            $response = json_decode($result, true);
            $key = 'mobileVerifyCode:' . $mobile;
            redis()->set($key, $verifyCode, 60 * 5);
            return ['code' => $response['code'], 'message' => $response['msg']];
        } catch (RequestException $exception) {
            $result = $exception->getResponse()->getBody()->getContents();
            $response = json_decode($result, true);
            return ['code' => $response['code'], 'message' => $response['msg'], 'detail' => $response['detail']];
        }
    }

    /**
     * 短信验证模板
     * @param $verifyCode
     * @return string
     */
    public function sendVerifyTemplate($verifyCode)
    {
        return sprintf('您的验证码是%s。如非本人操作，请忽略本短信', $verifyCode);
    }
}