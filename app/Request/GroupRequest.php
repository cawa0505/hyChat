<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/6
 * Time: 17:57
 */

namespace App\Request;


use Hyperf\Validation\Request\FormRequest;

class GroupRequest extends FormRequest
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
                'userIds' => 'require|array|integer'
            ];
        }
        if ($action == "update") {
            return [
                'id' => 'require|integer',
                'group_name' => 'string',
                "group_notice"=>"string"
            ];
        }
        return [];
    }
}