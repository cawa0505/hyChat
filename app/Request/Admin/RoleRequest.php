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
        $action = getAction($this->path());
        switch ($action) {
            case "create":
                $role = [
                    'role_name' => 'required',
                    'role_desc' => 'required',
                    'permission_ids' => 'required|array'
                ];
                break;
            case "update":
                $role = [
                    'id' => 'required',
                    'role_name' => 'required',
                    'role_desc' => 'required',
                    'permission_ids' => 'required|array'
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