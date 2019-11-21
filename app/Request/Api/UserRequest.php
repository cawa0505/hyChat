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
                "image_url" => "string",
                "nick_name" => "string",
                "sex" => "integer:0,1,2",
                "country_id" => "integer",
                "province_id" => "integer",
                "city_id" => "integer",
                "ind_sign" => "string",
            ];
        }
        return [];
    }
}
