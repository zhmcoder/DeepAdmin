<?php

return [

    // 数据库表字段类型 参考：https://laravel.com/docs/5.5/migrations#columns
    'db_table_field_type' => [
        'char' => '定长字符串（char）',
        'string' => '字符串（string）',
        'text' => '长文本数据（text）',
        'integer' => '整数值（integer）',
        'float' => '单精度浮点数值（float）',
        'decimal' => '双精度浮点数值（decimal）',
        'date' => '日期值（date）',
        'dateTime' => '时间值（dateTime）',
    ],
    // 表单类型
    'form_type' => [
        'input' => '输入框',
        'inputNumber' => '整数',
        'inputDecimal' => '小数',
        'textArea' => '长文本（textarea）',
        'wangEditor' => '富文本编辑器',
        'password' => '密码字符',
        'switch' => '开关',
        'option' => '单选框',
        'checkbox' => '复选框',
        'checkboxTable' => '复选框（连表查询）',
        'select' => '下拉单选',
        'selectTable' => '下拉单选（连表查询）',
        'selectRemote' => '下拉单选（远程搜索）',
        'selectMulti' => '下拉多选',
        'selectMultiTable' => '下拉多选（连表查询）',
        'inputTags' => '标签输入框',
        'upload' => '图片上传（单图）',
        'uploadMulti' => '图片上传（多图）',
        'uploadFile' => '文件上传（单个）',
        'dateTime' => '日期时间',
        'date' => '日期',
        'color' => '颜色选择',
        'countTable' => '统计数量',
        'timePicker' => '时间选择',
        'timePickerRange' => '时间段选择',
    ],
    // 表单是否显示
    'is_form_show' => [
        '1' => '不显示',
        '2' => '编辑显示',
        '3' => '添加显示',
        '4' => '始终显示',
    ],

    // 支持查询
    'is_search' => [
        '1' => '不支持',
        '2' => '快速查询',
        '3' => '输入查询',
        '4' => '下拉查询',
        '5' => '下拉查询-不可操作',
    ],

    'database' => [
        'connection' => '',
        'prefix' => 'admin_',
        // entities table and model.
        'entities_table' => 'admin_entities',
        'entities_model' => Andruby\DeepAdmin\Models\Entity::class,

        // entity_field table and model.
        'entity_fields_table' => 'admin_entity_fields',
        'entity_fields_model' => Andruby\DeepAdmin\Models\EntityField::class,
    ],

    'route' => [
        'domain' => env('ADMIN_DOMAIN', ''),
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),
        'api_prefix' => env('ADMIN_ROUTE_PREFIX', 'admin-api'),
        'namespace' => 'App\\Admin\\Controllers',
        'middleware' => ['web', 'admin'],
    ],

    'GRID_RELATED_DATA_CACHE_EXPIRE_TIME' => env('GRID_RELATED_DATA_CACHE_EXPIRE_TIME', 5 * 60),

];
