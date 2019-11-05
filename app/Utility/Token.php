<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/8
 * Time: 10:24
 */

namespace App\Utility;

use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class Token
 * @package App\Utility
 */
class Token
{
    /**
     * @var
     */
    private $key = 'api';

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * 生成签名
     * @param array $data
     * @return bool|string
     */
    public function encode(array $data = [])
    {
        if (empty($data)) {
            return false;
        }
        $time = time();
        $token = [
            'create_time' => $time, // 签发时间
            'expire_time' => $time + 60 * 60 * 24 * 30, // 过期时间
            'data' => $data

        ];
        return JWT::encode($token, $this->key);
    }

    /**
     * 解码签名
     * @param string $jwt
     * @return array
     */
    public function decode(string $jwt = '')
    {
        try {
            JWT::$leeway = 60;
            $decode = JWT::decode($jwt, $this->key, ['HS256']);
            return ['status' => 1, 'msg' => '解码签名成功', 'result' => (array)$decode];
        } catch (InvalidArgumentException $exception) {
            return ['status' => 0, 'msg' => '签名不能为空'];
        } catch (SignatureInvalidException $exception) {
            return ['status' => 0, 'msg' => '签名错误'];
        } catch (ExpiredException $exception) {
            return ['status' => 0, 'msg' => '签名已过期'];
        } catch (BeforeValidException $exception) {
            return ['status' => 0, 'msg' => '其它错误'];
        } catch (UnexpectedValueException $exception) {
            return ['status' => 0, 'msg' => '签名无效'];
        } catch (Exception $exception) {
            return ['status' => 0, 'msg' => $exception->getMessage()];
        }
    }
}