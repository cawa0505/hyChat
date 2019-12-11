<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Model\UserFriendModel;
use App\Request\Api\FriendArticleRequest;
use App\Service\Api\ArticleService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class FriendArticleController extends AbstractController
{
    /**
     * @Inject()
     * @var ArticleService
     */
    protected $articleService;

    /**
     * 朋友圈列表
     * @param FriendArticleRequest $request
     * @return ResponseInterface
     */
    public function articleList(FriendArticleRequest $request)
    {
        $params = $request->all();
        $result = $this->articleService->articleList($params,$this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 发布说说
     * @param FriendArticleRequest $request
     * @return ResponseInterface
     */
    public function pushArticle(FriendArticleRequest $request)
    {
        $params = $request->all();

        if (isset($params["picture"])) {

            $params["picture"] = json_encode($params["picture"]);

        }
        if (isset($params["location_lat_lng"])) {

            $params["location_lat_lng"] = json_encode($params["location_lat_lng"]);

        }

        $result = $this->articleService->pushArticle($this->getUserId(), $params);

        return $this->successResponse($result);
    }

    /**
     * 评论说说
     * @param FriendArticleRequest $request
     * @return ResponseInterface
     */
    public function commentArticle(FriendArticleRequest $request)
    {
        $params = $request->all();

        $result = $this->articleService->commentArticle($this->getUserId(), $params);

        return $this->successResponse($result);
    }
}
