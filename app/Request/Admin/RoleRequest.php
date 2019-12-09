<?php

declare(strict_types=1);

namespace App\Request\Admin;


use Hyperf\Validation\Request\FormRequest;

class RoleRequest extends FormRequest
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

    }
}