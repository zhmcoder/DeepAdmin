<?php
$router = app('router');

$router->group([
    'prefix' => config('deep_admin.route.prefix'),
    'middleware' => config('deep_admin.route.middleware'),
], function ($router) {

});

//
$router->group([
    'prefix' => config('deep_admin.route.api_prefix'),
    'middleware' => config('deep_admin.route.middleware'),
], function ($router) {

});
