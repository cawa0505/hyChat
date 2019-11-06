<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/5
 * Time: 11:01
 */

namespace App\Request\Auth;


use Hyperf\Validation\Request\FormRequest;

class RetrieveRequest extends FormRequest
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
            'phone' => 'required|numeric|digits:11',
            'code' => 'required|numeric|digits:6',
            'password' => 'required|min:8|alpha_dash',
        ];
    }
}