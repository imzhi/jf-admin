<?php

namespace Imzhi\JFAdmin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Pwd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '密码必填',
            'password.min' => '密码不能少于 6 个字符',
            'password.confirmed' => '重复密码不一致',
        ];
    }
}
