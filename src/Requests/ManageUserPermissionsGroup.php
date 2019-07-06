<?php

namespace Imzhi\JFAdmin\Requests;

use Imzhi\JFAdmin\Models\AdminUser;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ManageUserPermissionsGroup extends FormRequest
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
            'ids' => 'required|array',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ids.required' => '权限必选',
            'ids.array' => '权限数据错误',
            'name.required'  => '分组名称必填',
        ];
    }
}
