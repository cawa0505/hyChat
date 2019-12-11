<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 13:39
 */

namespace App\Service\Api;

use App\Constants\ApiCode;
use App\Model\FriendCircleComment;
use App\Model\FriendCircleMessage;
use App\Model\FriendCircleTimeline;
use App\Model\UserFriendModel;
use App\Service\BaseService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * Class FriendService
 * @package App\Service
 */
class ArticleService extends BaseService
{
    /**
     * @Inject()
     * @var FriendCircleMessage
     */
    private $articleModel;
    /**
     * @Inject()
     * @var FriendCircleTimeline
     */
    private $articleTime;
    /**
     * @Inject()
     * @var FriendCircleComment
     */
    private  $articleComment;
    /**
     * 朋友圈列表
     * @param array $params
     * @param $userId
     * @return array
     */
    public function articleList($params,$userId)
    {

        $result = $this->articleTime->getArticleList($params,$userId);
        $list_data=[];
        foreach ($result as $item){
            $_item["user_id"] = $item->article->user_id;
            $_item["image_url"] = $item->user->image_url;
            $_item["nick_name"] = $item->user->nick_name;
            $_item["fcm_id"] = $item->article->id;
            $_item["content"] = $item->article->content;
            $_item["picture"] = $item->article->picture?json_decode($item->article->picture):[];
            $_item["location"] = $item->article->location_desc;
            $_item["lat_lng"] = $item->article->location_lat_lng?json_decode($item->article->location_lat_lng):[];
            $_item["create_time"] = $item->article->create_time;
            $_item["comment_list"] = $item->commentList;
            $list_data[]=$_item;
        }
        return $this->success($list_data);
    }

    /**
     * 发布说说
     * @param $userId
     * @param $data
     * @return array|bool
     */
    public function pushArticle($userId, $data)
    {
        if (!$userId) {
            return $this->fail(ApiCode::AUTH_USER_NOT_EXIST);
        }

        $data["user_id"] = $userId;
        Db::beginTransaction();
        $articleId = $this->articleModel->createGetId($data);
        if (empty($articleId)) {
            Db::rollBack();
            $this->fail(ApiCode::OPERATION_FAIL);
        }
        //获取好友ID
        $friendIds = ( new UserFriendModel)->getFriendIdsByUserId($userId);
        $at_data[] = [
            "user_id"=>$userId,
            "fcm_id"=>$articleId,
            "is_own"=>1,
        ];
        foreach ($friendIds as $item){
            $at_data[] = [
                "user_id"=>$item["friend_id"],
                "fcm_id"=>$articleId,
                "is_own"=>0
            ];
        }
        //插入时间轴
        $result = $this->articleTime->createField($at_data);
        if (empty($result)) {
            Db::rollBack();
            $this->fail(ApiCode::OPERATION_FAIL);
        }
        Db::commit();
        return $this->success($result);
    }

    /**
     *评论说说
     * @param $userId
     * @param $data
     * @return array
     */
    public function commentArticle($userId, $data)
    {
        $data["user_id"] = $userId;
        $data["is_like"] = $data["type"] == 1;
        $result = $this->articleComment->createField($data);
        if (empty($result)) {
            $this->fail(ApiCode::OPERATION_FAIL);
        }
        return $this->success($result);
    }

}