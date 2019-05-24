<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminPost extends FormRequest
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
            'admin_name' => 'required|unique:admin|max:10',
            'pwd' => 'required',
            'repwd' => 'required|same:pwd',
            'email' => 'required'
        ];
    }
    public function messages(){
        return [
            'admin_name.required' =>'用户名不能为空',
            'admin_name.unique' =>'用户名不能重复',
            'admin_name.max' =>'用户名不能超过10字节',
            'pwd.required' =>'密码不能为空',
            'repwd.required' =>'确认密码不能为空',
            'repwd.same' =>'确认密码与密码不一致',
            'email.required' =>'邮箱不能为空'
        ];
    }
}
