<?php


namespace App\Request\Admin;


use Hyperf\Validation\Request\FormRequest;

class RoleRequest  extends FormRequest
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