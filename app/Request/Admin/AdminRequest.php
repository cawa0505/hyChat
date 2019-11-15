<?php


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
        if ($action == "login") {
            return [
                'username' => 'required|min:8|alpha_num',
                'password' => 'required|min:8|alpha_dash',
            ];
        }
        return  [];
    }

}