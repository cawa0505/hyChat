<?php

declare(strict_types=1);

namespace App\Request\Admin;


use Hyperf\Validation\Request\FormRequest;

class AdminRequest extends FormRequest
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
            case "login":
                $role = [
                    'username' => 'required|min:5|alpha_num',
                    'password' => 'required|min:5|alpha_dash',
                ];
                break;
            case "create":
                $role = [
                    'username' => 'required|min:5|alpha_num',
                    'password' => 'required|min:5|alpha_dash',
                    'mobile' => 'required',
                    'role' => 'required'
                ];
                break;
            case "update":
                $role = [
                    'id' => 'required',
                    'username' => 'required|min:5|alpha_num',
                    'password' => 'required|min:5|alpha_dash',
                    'mobile' => 'required',
                    'role' => 'required',
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