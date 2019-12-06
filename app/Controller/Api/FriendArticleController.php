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
     * @return ResponseInterface
     */
    public function articleList()
    {
        $result = $this->articleService->articleList($this->getUserId());
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
        $result = $this->articleService->pushArticle($this->getUserId(), $params);


        return $this->successResponse($result);
    }
}
