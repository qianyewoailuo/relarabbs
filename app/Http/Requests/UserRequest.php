<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 权限验证
     * 关于用户授权可以使用更为具有扩展性的方法,这里的权限验证一律忽略,即设为true
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 验证规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 表单内容验证规则
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            // unique 数据库唯一，在 users 数据表里，字段为 name，Auth::id() 指示将此 ID 排除在外。
            // unique:table,column,except idColumn
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar'  => 'mimes:jpg,jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208',
        ];
    }

    /**
     * 验证规则自定义错误提示信息
     */
    public function messages()
    {
        return [
            'avatar.mimes' => '头像图片格式类型必须为jpg,jpeg,bmp,png,gif',
            'avatar.dimensions' => '头像图片分辨率必须为208px以上',
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
