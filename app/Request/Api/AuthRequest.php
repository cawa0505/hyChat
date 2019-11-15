<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/7
 * Time: 10:56
 */

namespace App\Request\Api;


use Hyperf\Validation\Request\FormRequest;

/**
 * Class AuthRequest
 * @package App\Request
 */
class AuthRequest extends FormRequest
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
                'type' => 'required|integer',
                'account' => 'required|min:8|alpha_num',
                'password' => 'required|min:8|alpha_dash',
            ];
        }
        if ($action == "register") {
            return [
                'account' => 'required|min:8|alpha_num',
                'phone' => 'required|numeric|digits:11',
                'code' => 'required|numeric|digits:6',
                'password' => 'required|min:8|alpha_dash',
            ];
        }
        if ($action == "retrieve") {
            return [
                'phone' => 'required|numeric|digits:11',
                'code' => 'required|numeric|digits:6',
                'password' => 'required|min:8|alpha_dash',
            ];
        }
        return [];
    }
}