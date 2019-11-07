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
        switch ($action){
            case "create":
                return [
                    'userIds' => 'require|array|integer'
                ];
                break;
            case "update":
                return [
                    'id' => 'require|integer',
                    'group_name' => 'string',
                    "group_notice"=>"string"
                ];
                break;
            case "delete":
            case "join":
                return [
                    'id' => 'require|integer',
                ];
            default:
                return [];

        }

    }
}