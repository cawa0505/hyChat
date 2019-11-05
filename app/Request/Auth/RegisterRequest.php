<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/5
 * Time: 10:48
 */

namespace App\Request\Auth;


use Hyperf\Validation\Request\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'account' => 'required|min:8|alpha_num',
            'phone' => 'required|numeric|digits:11',
            'password' => 'required|min:8|alpha_dash',
            'code' => 'required|numeric|digits:6',
        ];
    }
}