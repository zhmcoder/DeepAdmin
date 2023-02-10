<?php

use Illuminate\Routing\Router;
use Andruby\DeepAdmin\Controllers\AuthController;
use Andruby\DeepAdmin\Controllers\RootController;

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.api_prefix'),
    'namespace' => '\Andruby\DeepAdmin\Controllers',
    'middleware' => config('deep_admin.route.middleware')
], function (Router $router) {
    //模型操作
    $router->resource('entities/entity-field', 'EntityFieldController')->names('entityField');
    $router->resource('entities/entity', 'EntityController')->names('entity');

    $router->get('entities/content/grid_sort_change', 'ContentController@grid_sort_change')->name('content.grid_sort_change ');
    $router->post('entities/content/sort_up_down', 'ContentController@sort_up_down')->name('content.sort_up_down ');
    $router->resource('entities/content', 'ContentController')->names('content');

    $router->get('remote/search', 'RemoteSearchController@search')->name('remote.search');
    $router->post('upload/images', 'UploadController@images')->name('upload.images');
    $router->post('upload/images_v4', 'UploadController@images_v4')->name('upload.images_v4');

    $router->post('_deep_admin_upload_image_', 'UploadController@uploadImage')->name('upload.image_handle');
    $router->post('_deep_admin_upload_file_', 'UploadController@uploadFile')->name('upload.file_handler');
    $router->post('_deep_admin_upload_xlsx_', 'UploadController@uploadXlsx')->name('upload.xlsx_handler');

    $router->get('auth/resetPwd', 'AuthController@resetPwd')->name('auth.resetPwd');
});

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.prefix'),
    'middleware' => config('deep_admin.route.middleware'),
], function (Router $router) {
    $authController = config('deep_admin.auth.controller', AuthController::class);
    $router->get('auth/login', $authController . '@getLogin')->name('admin.login');
    $router->post('auth/login', $authController . '@postLogin')->name('admin.post.login');
    $router->get('auth/logout', $authController . '@getLogout')->name('admin.logout');
    $router->get('auth/setting', $authController . '@getSetting')->name('admin.setting');
    $router->put('auth/setting', $authController . '@putSetting');
    $router->get('/', RootController::class . "@index")->name('admin.root');
});

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.api_prefix'),
    'namespace' => '\Andruby\DeepAdmin\Controllers'
], function (Router $router) {
    $router->get('scripts/{script}', 'ScriptController@show')->name('admin.scripts');
    $router->get('styles/{style}', 'StyleController@show')->name('admin.styles');

    $router->group(['middleware' => config('deep_admin.route.middleware')], function (Router $router) {
        $router->resource('auth/users', 'UserController')->names('admin.auth.users');
        $router->resource('auth/roles', 'RoleController')->names('admin.auth.roles');
        $router->resource('auth/permissions', 'PermissionController')->names('admin.auth.permissions');
        $router->resource('auth/menu', 'MenuController')->names('admin.auth.menu');
        $router->post('auth/menu-order', 'MenuController@menuOrder')->name('admin.auth.menu.order');
        $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']])->names('admin.auth.logs');
        $router->post('_handle_upload_image_', 'HandleController@uploadImage')->name('admin.handle-upload-image');
        $router->post('_handle_upload_file_', 'HandleController@uploadFile')->name('admin.handle-upload-file');
        $router->post('_handle_upload_xlsx_', 'HandleController@uploadXlsx')->name('admin.handle-upload-xlsx');
        $router->post('_handle_form_', 'HandleController@handleForm')->name('admin.handle-form');
        $router->post('_handle_action_', 'HandleController@handleAction')->name('admin.handle-action');
    });
});
