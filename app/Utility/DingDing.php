<?php

namespace App\Utility;

use App\Traits\Request;

/**
 * Class DingDing
 * @package App\Utility
 */
class DingDing
{
    use Request;

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     */
    public function sendText($content, $mobiles = [], $isAtAll = false)
    {
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content
            ],
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $isAtAll
            ]
        ];
        $this->request($data);
    }

    /**
     * 发送链接消息
     * @param string $title 消息标题
     * @param string $text 消息内容。如果太长只会部分展示
     * @param string $messageUrl 点击消息跳转的URL
     * @param string $picUrl 图片URL
     */
    public function sendLink($title, $text, $messageUrl, $picUrl = '')
    {
        $data = [
            'msgtype' => 'link',
            'link' => [
                'title' => $title,
                'text' => $text,
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl,
            ]
        ];
        $this->request($data);
    }

    /**
     * 发送Markdown消息
     * @param string $title 首屏会话透出的展示内容
     * @param string $text markdown格式的消息
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     */
    public function sendMarkdown($title, $text, $mobiles = [], $isAtAll = false)
    {
        $data = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $text,
            ],
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $isAtAll
            ]
        ];
        $this->request($data);
    }

    /**
     * @param $postData
     * @return string
     */
    private function request($postData)
    {
        return $this->requestPostJson('https://oapi.dingtalk.com', '/robot/send?access_token=' . env('ACCESS_TOKEN'), $postData);
    }
}
