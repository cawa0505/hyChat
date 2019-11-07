<?php

declare(strict_types=1);

namespace App\Request;

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
                "sex" => "required|int:0,1,2",
                "country_id" => "required|int",
                "province_id" => "required|int",
                "city_id" => "required|int",
                "ind_sign" => "required|string",
            ];
        }
        return [];
    }
}
