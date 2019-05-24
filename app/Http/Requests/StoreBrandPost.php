<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandPost extends FormRequest
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
            'brand_name' => 'required|unique:brand|max:10',
            'brand_desc' => 'required',
            'brand_url' => 'required'
        ];
    }
    public function messages(){
        return [
            'brand_name.required' =>'品牌名不能为空',
            'brand_name.unique' =>'品牌名不能重复',
            'brand_name.max' =>'品牌名不能超过10字节',
            'brand_desc.required' =>'品牌描述不能为空',
            'brand_url.required' =>'品牌网址不能为空'
        ];
    }
}
