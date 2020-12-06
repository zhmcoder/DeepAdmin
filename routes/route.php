<?php

use Illuminate\Routing\Router;

Route::group([
    'domain' => config('deep_admin.route.domain'),
    'prefix' => config('deep_admin.route.api_prefix'),
    'namespace' => '\Andruby\DeepAdmin\Controllers',
    'middleware' => config('admin.route.middleware')
], function (Router $router) {
    //模型操作
    $router->resource('entities/entity-field', 'EntityFieldController')->names('entityField');
    $router->resource('entities/entity', 'EntityController')->names('entity');

    $router->get('entities/content/grid_sort_change', 'ContentController@grid_sort_change')->name('content.grid_sort_change ');
    $router->post('entities/content/sort_up_down', 'ContentController@sort_up_down')->name('content.sort_up_down ');
    $router->resource('entities/content', 'ContentController')->names('content');

    $router->get('remote/search', 'RemoteSearchController@search')->name('remote.search');
    $router->post('upload/images', 'UploadController@images')->name('upload.images');
});

