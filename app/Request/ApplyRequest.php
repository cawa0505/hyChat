<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/7
 * Time: 11:01
 */

namespace App\Request;


use Hyperf\Validation\Request\FormRequest;

class ApplyRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $action = getAction($this->path());
        if ($action == "create") {
            return [
                'friendId' => 'required'
            ];
        }
        if ($action == "review") {
            return [
                'applyId' => 'required',
                'status' => 'required'
            ];
        }
        return [];
    }

}