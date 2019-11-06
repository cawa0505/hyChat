<?php
/**
 * Created by PhpStorm.
 * User: phpstorm
 * Date: 2019/10/9
 * Time: 17:47
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Service\ApplyService;
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
     * @return ResponseInterface
     */
    public function create()
    {
        $request = $this->request->all();
        $rules = ['friendId' => 'required'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->errorResponse($errorMessage);
        }
        $result = $this->applyService->createApply($request, $this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * @return ResponseInterface
     */
    public function record()
    {
        $result = $this->applyService->getApplyByUserId($this->getUserId());
        return $this->successResponse($result);
    }

    /**
     * 审核好友申请
     * @return ResponseInterface
     */
    public function review()
    {
        $request = $this->request->getParsedBody();
        $rules = ['applyId' => 'required', 'status' => 'required'];
        // 表单验证
        $validator = $this->validationFactory->make($request, $rules);
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first();
            return $this->errorResponse($errorMessage);
        }
        $result = $this->applyService->review($request, $this->getUserId());
        return $this->successResponse($result);
    }
}