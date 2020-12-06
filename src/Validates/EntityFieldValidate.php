<?php

namespace Andruby\DeepAdmin\Validates;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntityFieldValidate extends FormRequest
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
                'max:64',
                'regex:/^[0-9a-zA-Z$_]+$/',
                Rule::unique(config('deep_admin.database.entity_fields_table'), 'name')
                    ->where('id', '{{id}}')->ignore('{{id}}', 'id')
            ],
            'entity_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'form_name' => [
                'required',
                'max:20'
            ],
            'form_params' => [
                'required_if:form_type,option,checkbox,select',
                'max:1024'
            ],
            'order' => [
                'required',
                'integer'
            ]
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
                'unique' => '字段名称已存在',
                'required' => '字段名称不能为空',
                'max' => '字段名称长度不能大于64',
                'regex' => '字段名称格式有误',
            ],
            'entity_id' => [
                'required' => '模型不能为空',
                'integer' => '模型格式有误',
                'min' => '模型最小值为1',
            ]
        ];
    }
}
