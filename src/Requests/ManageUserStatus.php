<?php

namespace Imzhi\InspiniaAdmin\Requests;

use App\Models\AdminUser;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ManageUserStatus extends FormRequest
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
        $status_in = [
            AdminUser::STATUS_DISABLE,
            AdminUser::STATUS_ENABLE,
        ];
        return [
            'user_id' => 'required',
            'status' => [
                'required',
                Rule::in($status_in),
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => '参数错误',
            'status.required'  => '状态必选',
            'status.in'  => '状态有误',
        ];
    }
}
