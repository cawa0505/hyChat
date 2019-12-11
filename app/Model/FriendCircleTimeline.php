<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;

/**
 * Class FriendCircleTimeline
 * @package App\Model
 */
class FriendCircleTimeline extends BaseModel
{
    /**
     * 朋友圈时间轴
     *
     * @var string
     */
    protected $table = 'friend_circle_timeline';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'uid' => 'integer', 'fcm_id' => 'integer', 'is_own' => 'integer'];

    /**
     * 一对一获取文章
     * @return HasOne
     */
    public function article()
    {
        return $this->hasOne(FriendCircleMessage::class, "id", "fcm_id");
    }

    /**
     * 一对多获取评论
     * @return HasMany
     */
    public function commentList()
    {
        return $this->hasMany(FriendCircleComment::class, "fcm_id", "fcm_id");
    }

    /**
     * 一对一用户信息
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(UserModel::class,"id","user_id")->select(["image_url","nick_name"]);
    }

    /**
     * 获取某个用户的朋友圈
     * @param $params
     * @param $userId
     * @param int $size
     * @return LengthAwarePaginatorInterface
     */
    public function getArticleList($params,$userId, $size = 10)
    {
//        if (isset($params["size"])&&$params["size"]) $size = $params["size"];
        $query = $this->newQuery()
            ->where("user_id", $userId)->paginate($size);
        $data["currentPage"]=$query->currentPage();
        $data["total"]=$query->total();
        $data["last_page"]=$query->lastPage();
        foreach ($query as &$item) {
            $item->article;
            $item->commentList;
            $item->user;
        }
        $data["data"]= $query->items();

        return $query;
    }
    /**
     *
     */

}