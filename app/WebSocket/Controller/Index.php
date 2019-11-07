<?php

namespace App\WebSocket\Controller;

use App\WebSocket\Common;

/**
 * Class Index
 * @package App\WebSocket\Controller
 */
class Index extends Common
{
    /**
     * {"controller":"Index","action":"index","content":"123456"}
     */
    public function index()
    {
        $this->sendToUsers([1], $this->getData());
    }
}