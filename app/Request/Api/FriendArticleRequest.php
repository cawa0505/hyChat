<?php

declare(strict_types=1);

namespace App\Request\Api;

use Hyperf\Validation\Request\FormRequest;

class FriendArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $action = getAction($this->path());
        switch ($action) {
            case "articleList":
                return [
                    'page' => 'integer',
                    'size' => 'integer',
                ];
                break;
            case "pushArticle":
                return [
                    'content' => 'required|string',
                    'picture' => 'array',
                    'location_desc' => 'string',
                    'location_lat_lng' => 'array',
                ];
                break;
            case "commentArticle":
                return [
                    "fcm_id" => "required|integer",
                    "content" => "required|string",
                    "type"=>"required|int:1,2"
                ];
                break;
        }
        return [];
    }
}
