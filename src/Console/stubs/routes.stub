<?php

JFAdmin::routes();

Route::group([
    'prefix' => config('jfadmin.route.prefix'),
    'namespace' => config('jfadmin.route.namespace'),
    'as' => config('jfadmin.route.as'),
    'middleware' => config('jfadmin.route.middleware'),
    'domain' => config('jfadmin.route.domain'),
], function ($router) {
    // 首页
    $router->get('', 'HomeController@showIndex')->name('show.index');
});
