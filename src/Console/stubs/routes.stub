<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->post('/fileupload', 'FileuploadController@index')->name('fileupload'); // Image Upload API
    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('administrators', AdministratorController::class);

});
