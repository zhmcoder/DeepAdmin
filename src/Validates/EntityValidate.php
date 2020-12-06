<?php

namespace Andruby\DeepAdmin\Validates;

use Illuminate\Foundation\Http\FormRequest;

class EntityValidate extends FormRequest
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
            'name' => [
                'required',
                'max:50',
                'unique:' . config('deep_admin.database.entities_table') . ',name,{{id}}'
            ],
            'table_name' => [
                'required',
                'max:64',
                'unique:' . config('deep_admin.database.entities_table') . ',table_name,{{id}}',
                'regex:/^[0-9a-zA-Z$_]+$/'
            ],
            'description' => 'max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {

        return [
            'name' => [
                'unique' => '名称已存在',
                'required' => '名称不能为空',
                'max' => '名称长度不能大于50',
            ],
            'table_name' => [
                'required' => '表名称不能为空',
                'max' => '表名称长度不能大于64',
                'unique' => '表名称已存在',
                'regex' => '数据库表名不合规范',
            ]
        ];
    }
}
