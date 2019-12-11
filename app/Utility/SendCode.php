<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/10
 * Time: 16:49
 */

declare(strict_types=1);

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
     * @param $phone
     * @return array
     */
    public function send($phone)
    {
        $verifyCode = mt_rand(100000, 999999);
        $data = [
            'apikey' => env("API_KEY",'ba93d3d8d3964daa94da3503b9afc5ac'),
            'text' => $this->sendVerifyTemplate($verifyCode),
            'mobile' => $phone,
        ];
        try {
            $result = $this->requestPost("https://sms.yunpian.com/", 'v2/sms/single_send.json', $data);
            $response = json_decode($result, true);
            $key = 'phoneVerifyCode:' . $phone;
            redis()->set($key, $verifyCode, 60 * 15);
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