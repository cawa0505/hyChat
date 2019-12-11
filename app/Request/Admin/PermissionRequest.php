<?php

declare(strict_types=1);

namespace App\Request\Admin;


use Hyperf\Validation\Request\FormRequest;

class PermissionRequest extends FormRequest
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
        switch ($action) {
            case "create":
                $role = [
                    'name' => 'required',
                    'url' => 'required'
                ];
                break;
            case "update":
                $role = [
                    'id' => 'required',
                    'name' => 'required',
                    'url' => 'required'
                ];
                break;
            case "delete":
                $role = [
                    'id' => 'required'
                ];
                break;
            default:
                $role = [];
                break;
        }

        return $role;
    }
}