<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/11/6
 * Time: 16:02
 */

namespace App\Request\Apply;


use Hyperf\Validation\Request\FormRequest;

class ReviewRequest extends FormRequest
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
            'applyId' => 'required',
            'status' => 'required'
        ];
    }

}