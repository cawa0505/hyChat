<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 17:47
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Request\Apply\CreateRequest;
use App\Request\Apply\ReviewRequest;
use App\Service\ApplyService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ApplyController
 * @package App\Controller\Api
 */
class ApplyController extends AbstractController
{
    /**
     * @Inject()
     * @var ApplyService
     */
    private $applyService;

    /**
     * 添加好友申请
     * @param CreateRequest $request
     * @return ResponseInterface
     */
    public function create(CreateRequest $request)
    {
        $result = $this->applyService->createApply($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 审核好友申请
     * @param ReviewRequest $request
     * @return ResponseInterface
     */
    public function review(ReviewRequest $request)
    {
        $result = $this->applyService->reviewApply($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }
}