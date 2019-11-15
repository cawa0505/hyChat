<?php

declare(strict_types=1);

namespace App\Request\Api;

use Hyperf\Validation\Request\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        if ($action == "updateUserInfo") {
            return [
                "image_url" => "required|string",
                "nick_name" => "required|string",
                "sex" => "required|integer:0,1,2",
                "country_id" => "required|integer",
                "province_id" => "required|integer",
                "city_id" => "required|integer",
                "ind_sign" => "required|string",
            ];
        }
        return [];
    }
}
