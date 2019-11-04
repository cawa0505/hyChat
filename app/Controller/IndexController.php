<?php

declare(strict_types=1);

namespace App\Controller;

class IndexController extends AbstractController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return $this->render->render('index', ['message' => '欢迎使用Hyperf']);
    }
}