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

        $router->get('/controller', 'Controller@index');
    });
});
