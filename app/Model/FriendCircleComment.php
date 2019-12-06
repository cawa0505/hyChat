<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * Class FriendCircleComment
 * @package App\Model
 */
class FriendCircleComment extends Model
{
    /**
     * 朋友圈评论表
     *
     * @var string
     */
    protected $table = 'friend_circle_comment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'fcmid' => 'integer', 'uid' => 'integer', 'like_count' => 'integer'];
}