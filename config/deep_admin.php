<?php

return [
    'name' => env('ADMIN_NAME', 'DeepAdmin'), // 后台名称 null不显示
    'title' => env('ADMIN_TITLE', 'DeepAdmin'), // 后台标题
    'loginDesc' => env('LOGIN_DESC', 'DeepAdmin 是开箱即用的 Laravel 后台扩展'), // 登录界面描述
    'logo_show' => false,
    'logo' => null, // logo 地址 null为内置默认 分为黑暗和明亮两种
    'logo_mini' => null,
    'logo_light' => null,
    'logo_mini_light' => null,
    'copyright' => env('ADMIN_COPYRIGHT', 'Copyright © 2022 DeepAdmin'), // 版权
    'default_avatar' => 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png', // 默认头像
    'login_background_image' => 'https://gw.alipayobjects.com/zos/rmsportal/TVYTbAXWheQpRcWDaDMu.svg', // 登录页面背景
    // 登录框默认用户
    'auto_user' => [
        'username' => '',
        'password' => ''
    ],
    // 底部菜单
    'footerLinks' => [
        [
            'href' => 'https://github.com/zhmcode/deep-admin',
            'title' => '官网'
        ],
        [
            'href' => 'https://deepadmin.cc',
            'title' => '文档'
        ]
    ],
    'unique_opened' => false, // 是否只保持一个子菜单的展开
    'bootstrap' => app_path('Admin/bootstrap.php'),
    'route' => [
        'domain' => env('ADMIN_DOMAIN', ''),
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'dadmin'),
        'api_prefix' => env('ADMIN_ROUTE_API_PREFIX', 'admin-api'),
        'namespace' => 'App\\Admin\\Controllers',
        'middleware' => ['web', 'admin'],
    ],
    'directory' => app_path('Admin'),
    'https' => env('ADMIN_HTTPS', false),
    'auth' => [
        'controller' => App\Admin\Controllers\AuthController::class,
        'guard' => 'admin',
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model' => Andruby\DeepAdmin\Auth\Database\Administrator::class,
            ],
        ],
        'remember' => true, // Add "remember me" to login form
        'redirect_to' => 'auth/login',// Redirect to the specified URI when user is not authorized.
        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
            '_handle_action_',
        ],
    ],
    'upload' => [
        'disk' => 'public',// Disk in `config/filesystem.php`.
        'uniqueName' => false,
        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file' => 'files',
        ],
        //文件上传类型
        'xlsx' => 'xls,xlsx',
        'file' => 'doc,docx,mp3,mp4,apk',
        'image' => 'jpeg,bmp,png,gif,jpg',
        'image_handle_router' => 'upload.image_handle',
        'file_handle_router' => 'upload.file_handler',
    ],
    'database' => [
        'connection' => '', // Database connection for following tables.
        'users_table' => 'admin_users', // User tables and model.
        'users_model' => Andruby\DeepAdmin\Auth\Database\Administrator::class,
        'roles_table' => 'admin_roles', // Role table and model.
        'roles_model' => Andruby\DeepAdmin\Auth\Database\Role::class,
        'permissions_table' => 'admin_permissions', // Permission table and model.
        'permissions_model' => Andruby\DeepAdmin\Auth\Database\Permission::class,
        'menu_table' => 'admin_menu', // Menu table and model.
        'menu_model' => Andruby\DeepAdmin\Auth\Database\Menu::class,
        'operation_log_table' => 'admin_operation_log', // Pivot table for table above.
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table' => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table' => 'admin_role_menu',

        'prefix' => 'admin_',
        'entities_table' => 'admin_entities', // entities table and model.
        'entities_model' => Andruby\DeepAdmin\Models\Entity::class,
        'entity_fields_table' => 'admin_entity_fields', // entity_field table and model.
        'entity_fields_model' => Andruby\DeepAdmin\Models\EntityField::class,
    ],
    //操作日志
    'operation_log' => [
        'enable' => true,
        // Only logging allowed methods in the list
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
        /*
         * Routes that will not log to database.
         * All method to path like=>admin/auth/logs
         * or specific method to path like=>get:admin/auth/logs.
         */
        'except' => [
            'admin/auth/logs*',
            'admin-api/auth/logs*',
            'admin',
            'admin-api'
        ],
    ],
    'check_route_permission' => true,
    'check_menu_roles' => true,
    'map_provider' => 'google',
    'show_version' => true,
    'show_environment' => true,
    'menu_bind_permission' => true,
    'which-composer' => 'composer',
    'markdown_toolbars' => [
        'bold' => true,
        'italic' => true,
        'header' => true,
        'underline' => true,
        'strikethrough' => true,
        'mark' => true,
        'superscript' => true,
        'subscript' => true,
        'quote' => true,
        'ol' => true,
        'ul' => true,
        'link' => true,
        'imagelink' => true,
        'code' => true,
        'table' => true,
        'fullscreen' => true,
        'readmodel' => true,
        'htmlcode' => true,
        'help' => true,
        /* 1.3.5 */
        'undo' => true,
        'redo' => true,
        'trash' => true,
        'save' => true,
        /* 1.4.2 */
        'navigation' => true,
        /* 2.1.8 */
        'alignleft' => true,
        'aligncenter' => true,
        'alignright' => true,
        /* 2.2.1 */
        'subfield' => true,
        'preview' => true
    ],
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
        'markdown' => 'markdown编辑器',
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
    'GRID_RELATED_DATA_CACHE_EXPIRE_TIME' => env('GRID_RELATED_DATA_CACHE_EXPIRE_TIME', 5 * 60),
];
