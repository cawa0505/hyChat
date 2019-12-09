<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 11:27
 */

declare(strict_types=1);

namespace App\Utility;

/**
 * Class RsaEncryption
 * @package App\Utility
 */
class RsaEncryption
{
    /**
     * @var resource
     */
    private $public;
    /**
     * @var bool|resource
     */
    private $private;
    /**
     * @var false|string
     */
    private $public_key;

    /**
     * RsaEncryption constructor.
     * @param $filePrivate
     * @param $filePublic
     */
    public function __construct($filePrivate = "/public/private_key.pem", $filePublic = "/public/public_key.pem")
    {
        $this->private = openssl_get_privatekey(file_get_contents(BASE_PATH . $filePrivate));
        $this->public_key = file_get_contents(BASE_PATH . $filePublic);
        $this->public = openssl_get_publickey($this->public_key);
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * 私钥加密
     * @param $data
     * @return string
     */
    public function privateEncrypt($data)
    {
        $crypto = '';

        foreach (str_split(json_encode($data), 117) as $chunk) {
        openssl_private_encrypt($chunk, $encryptData, $this->private);
        $crypto .= $encryptData;
    }
        //加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        $encrypted = $this->urlSafe_b64encode($crypto);
        return $encrypted;
    }


    /**
     * 私钥加密的内容通过公钥可用解密出来
     * @param $encrypted
     * @return mixed
     */
    public function publicDecrypt($encrypted)
    {
        $crypto = '';
        foreach (str_split($this->urlsafe_b64decode($encrypted), 128) as $chunk) {
            openssl_public_decrypt($chunk, $decryptData, $this->public);
            $crypto .= $decryptData;
        }
        return json_decode($crypto);
    }

    /**
     * 加密码时把特殊符号替换成URL可以带的内容
     * @param $string
     * @return string
     */
    private function urlSafe_b64encode($string)
    {
        $data = base64_encode($string);
        return $data;
    }

    /**
     * 解密码时把转换后的符号替换特殊符号
     * @param $string
     * @return bool|string
     */
    private function urlSafe_b64decode($string)
    {
        return base64_decode($string);
    }

    /**
     * 公钥加密
     * @param $data
     * @return string
     */
    public function publicEncrypt($data)
    {
        $crypto = "";
        foreach (str_split(json_encode($data), 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $this->public_key);
            $crypto .= $encryptData;
        }
        $encrypted = $this->urlSafe_b64encode($crypto);
        return $encrypted;
    }

    /**
     * 私钥解密
     * @param $encrypted
     * @return mixed
     */
    public function privateDecrypt($encrypted)
    {
        $crypto = '';
        foreach (str_split($this->urlSafe_b64decode($encrypted), 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $this->private);
            $crypto .= $decryptData;
        }
        $crypto = urldecode($crypto);
        return json_decode($crypto, true);
    }
}
