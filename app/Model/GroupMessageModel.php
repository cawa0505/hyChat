<?php


namespace App\Model;


use App\Utility\Client\MongoModel;

class GroupMessageModel extends MongoModel
{
    /**
     * 表名
     * @var
     */
    protected $table = "group_message";
}