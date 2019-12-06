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
        if ($action == "pushArticle") {
            return [
                'content' => 'required|string',
                'picture' => 'array',
                'location_desc' => 'string',
                'location_lat_lng' => 'string',
            ];
        }
    }
}
