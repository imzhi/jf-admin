<?php

namespace Imzhi\JFAdmin\Requests;

use Imzhi\JFAdmin\Models\AdminUser;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ManageUserRolesDistribute extends FormRequest
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
            'id' => 'required',
            'permission_ids' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '参数错误',
            'role_ids.array'  => '权限数据错误',
        ];
    }
}
