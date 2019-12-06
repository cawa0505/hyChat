<?php

declare (strict_types=1);
namespace App\Model;


/**
 * Class FriendCircleMessage
 * @package App\Model
 */
class FriendCircleMessage extends BaseModel
{
    /**
     * 好友文章发布
     *
     * @var string
     */
    protected $table = 'friend_circle_message';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'uid' => 'integer'];


}