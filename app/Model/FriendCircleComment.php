<?php

declare (strict_types=1);
namespace App\Model;


/**
 * Class FriendCircleComment
 * @package App\Model
 */
class FriendCircleComment extends BaseModel
{
    /**
     * 朋友圈评论表
     *
     * @var string
     */
    protected $table = 'friend_circle_comment';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fcm_id',"user_id","nickname","content","is_like","create_time"];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'fcmid' => 'integer', 'uid' => 'integer', 'like_count' => 'integer'];
}