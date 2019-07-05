<?php

namespace Imzhi\InspiniaAdmin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageUserCreate extends FormRequest
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
            'name' => 'required_without:id',
            'password' => 'required_without:id|nullable|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required_without' => '用户账号必填',
            'password.required_without'  => '密码必填',
            'password.min'  => '密码不能少于 6 个字符',
            'password.confirmed'  => '重复密码不正确',
        ];
    }
}
