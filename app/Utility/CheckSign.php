<?php

declare(strict_types=1);

namespace App\Utility;


class CheckSign
{
    /**
     * 验证签名
     * @param $arr
     * @return bool
     */
    public function checkSign($arr)
    {
        //获取签名
        $sing = $this->getSign($arr);
        if ($sing == $arr['sign']) {
            return true;
        } else {
            logger()->error("签名错误".$arr['sign']);
            return false;
        }
    }

    /**
     * 获取签名 待签名的数组
     * @param $arr
     * @return string
     */
    protected function getSign($arr)
    {
        //去除数组中的空值
//        $arr = array_filter($arr);
        //如果数组中有签名删除签名
        if (isset($arr['sign'])) {
            unset($arr['sign']);
        }
        //1.按照键名字典排序
        ksort($arr);
        //2.生成URL格式的字符串
        //http_build_query()中文自动转码需要处理下
        $str = http_build_query($arr);
        //3.appid=dkdfg&body=2347%E4%BA%AC%E4%B8%9C%E5%95%86%E5%9F%8E&mch_id=sdfgd&key=kkkkksdio87923CBEF716EF1A065E6979DE3170BE3B6B8
        $str = urldecode($str);
//        echo $str;
        return strtoupper(md5($str));
    }



    /**
     * 生成签名
     * @param $params
     * @return mixed
     */
    public function setSign($params)
    {
        $params['sign'] = $this->getSign($params);

        return $params;
    }
}