<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 17:47
 */

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Request\Api\ApplyRequest;
use App\Service\Api\ApplyService;
use Hyperf\Di\Annotation\Inject;
use MongoDB\Driver\Exception\Exception;
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
     * @param ApplyRequest $request
     * @return ResponseInterface
     */
    public function create(ApplyRequest $request)
    {
        $result = $this->applyService->createApply($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 申请记录
     * @return ResponseInterface
     */
    public function record()
    {
        $result = $this->applyService->getApplyByUserId($this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 审核好友申请
     * @param ApplyRequest $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function review(ApplyRequest $request)
    {
        $result = $this->applyService->reviewApply($request->all(), $this->getUserId());
        return $this->successResponse($result);
    }
}