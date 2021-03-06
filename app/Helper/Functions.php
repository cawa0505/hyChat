<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/9/30
 * Time: 14:44
 */

declare(strict_types=1);

use App\Model\SysConfig;
use App\Utility\Client\MongoModel;
use Hyperf\Framework\Logger\StdoutLogger;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Server\ServerFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Swoole\Server;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;


if (!function_exists("container")) {
    /**
     * @return ContainerInterface
     */
    function container()
    {
        return Hyperf\Utils\ApplicationContext::getContainer();
    }
}


if (!function_exists('dd')) {
    /**
     * @param $data
     */
    function dd(...$data)
    {
        stdout()->info("-----------------打印调试开启-----------------");
        print_r($data);
        stdout()->info("-----------------打印调试结束-----------------");
    }
}


if (!function_exists("logger")) {
    /**
     * 日志记录
     * @param string $name 日志名字
     * @return LoggerInterface
     */
    function logger($name = 'default')
    {
        return container()->get(LoggerFactory::class)->get($name, $name);
    }
}

if (!function_exists("stdout")) {
    /**
     * 终端日志
     * @return StdoutLogger
     */
    function stdout()
    {
        return container()->get(StdoutLogger::class);
    }
}

if (!function_exists('makePasswordHash')) {
    /**
     * 从一个明文值生产哈希
     * @param string $value 需要生产哈希的原文
     * @param integer $cost 递归的层数 可根据机器配置调整以增加哈希的强度
     * @return false|string 返回60位哈希字符串 生成失败返回false
     */
    function makePasswordHash($value, $cost = 10)
    {
        return password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}

if (!function_exists('validatePasswordHash')) {
    /**
     * 校验明文值与哈希是否匹配
     * @param string $value
     * @param string $hashValue
     * @return bool
     */
    function validatePasswordHash($value, $hashValue)
    {
        return password_verify($value, $hashValue);
    }
}

if (!function_exists('setContext')) {
    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    function setContext($key, $value)
    {
        return Hyperf\Utils\Context::set($key, $value);
    }
}

if (!function_exists('getContext')) {
    /**
     * @param $key
     * @return mixed|null
     */
    function getContext($key)
    {
        return Hyperf\Utils\Context::get($key);
    }
}

if (!function_exists('hasContext')) {
    /**
     * @param $key
     * @return mixed|null
     */
    function hasContext($key)
    {
        return Hyperf\Utils\Context::has($key);
    }
}

/**
 * server 实例 基于 server
 */
if (!function_exists('server')) {
    /**
     * @return Server
     */
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}


/**
 * websocket 实例
 */
if (!function_exists('websocket')) {
    /**
     * @return WebSocketServer
     */
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}

/**
 * websocket frame 实例
 */
if (!function_exists('frame')) {
    /**
     * @return mixed
     */
    function frame()
    {
        return container()->get(Frame::class);
    }
}

if (!function_exists("getMode")) {
    /**
     * @return mixed
     */
    function getMode()
    {
        return env("APP_NAME");
    }
}

if (!function_exists("redis")) {
    /**
     * @param string $name
     * @return Redis
     */
    function redis($name = 'default')
    {
        /** @var RedisFactory $redis */
        $redis = container()->get(RedisFactory::class);
        return $redis->get($name);
    }
}

if (!function_exists("queue")) {
    /**
     * 队列消费
     * @param $class
     * @return bool
     */
    function queue($class)
    {
        /** @var Hyperf\Amqp\Producer $producer */
        $producer = container()->get(Hyperf\Amqp\Producer::class);
        return $producer->produce($class);
    }
}

if (!function_exists('mongoModel')) {
    /**
     * @return MongoModel
     */
    function mongoModel()
    {
        return container()->get(MongoModel::class);
    }
}

if (!function_exists('getAction')) {

    /**
     * 获取控制器方法名
     * @param $path
     * @return string
     */
    function getAction($path)
    {
        $path = explode("/", $path);
        return trim(array_pop($path), " ");
    }
}


if (!function_exists('isOneArray')) {
    /**
     * 检测一维数组
     * @param $data
     * @return bool
     */
    function isOneArray($data)
    {
        if (!is_array(reset($data))) {
            return true;
        }

        return false;
    }
}

if (!function_exists("arrayToTree")) {
    /**
     * @param $array
     * @return array
     */
    function arrayToTree($array)
    {
        $items = array();
        foreach ($array as $value) {
            $items[$value['menu_id']] = $value;
        }
        $tree = array();
        foreach ($items as $key => $item) {
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['son'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
}
if (!function_exists("sysConfig")) {
    /**
     * 获取配置
     * @param $key
     * @param int $default
     * @return int
     */
    function sysConfig($key, $default = 0)
    {
        return container()->get(SysConfig::class)->getConfig($key) ?? $default;
    }
}