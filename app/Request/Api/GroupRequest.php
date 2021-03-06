<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/6
 * Time: 17:57
 */

declare(strict_types=1);

namespace App\Request\Api;


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

        $path=$this->path();
        $action = getAction($path);
        switch ($action) {
            case "create":
                return [
                    'userIds' => 'required|array'
                ];
                break;
            case "update":
                return [
                    'id' => 'required|integer',
                    'group_name' => 'string',
                    "group_notice" => "string"
                ];
                break;
            case "delete":
            case "join":
            case "invite":
            case "memberList":
                return [
                    'id' => 'required',
                ];
                break;
            case "updateNick":
                return [
                    'id' => 'required|integer',
                    'group_nick_name' => 'required|string',
                ];
                break;
            case "appointAdmin":
                return [
                    'groupId' => 'required|integer',
                    'userId' => 'required|integer',
                ];
                break;
            default:
                return [];
        }

    }

}