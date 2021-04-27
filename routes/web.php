<?php

/** @var \Laravel\Lumen\Routing\Router $router */
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function() use ($router){
    $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
    $router->post('register', ['as' => 'register', 'uses' => 'AuthController@register']);
    $router->post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

    $router->group(['middleware' => 'auth'], function() use ($router) {
        
        //category
        $router->group(['prefix' => 'category'], function() use ($router) {
            $router->get('/', ['as' => 'category', 'uses' => 'CategoryController@get_all_categories']);
            $router->get('/{id}', ['as' => 'detail_category', 'uses' => 'CategoryController@get_detail_category']);
        });

        //category
        $router->group(['prefix' => 'classroom'], function() use ($router) {
            $router->post('/enroll', ['as' => 'enroll', 'uses' => 'ClassroomController@enroll_classroom']);

            $router->get('/', ['as' => 'classroom', 'uses' => 'ClassroomController@get_all_classroom']);
            $router->get('/{id}', ['as' => 'detail_classroom', 'uses' => 'ClassroomController@get_detail_classroom']);
        });

        //profile
        $router->group(['prefix' => 'profile'], function() use ($router){
            $router->get('/', ['as' => 'profile', 'uses' => 'ProfileController@get_user_profile']);
            $router->get('/get_avatar/{id}', ['as' => 'get_avatar', 'uses' => 'ProfileController@get_avatar']);
            $router->patch('/update_profile/{id}', ['as' => 'update_profile', 'uses' => 'ProfileController@update_profile']);
            $router->patch('/update_account/{id}', ['as' => 'update_account', 'uses' => 'ProfileController@update_account']);
        });

        $router->get('/controller', 'Controller@index');
    });
});
