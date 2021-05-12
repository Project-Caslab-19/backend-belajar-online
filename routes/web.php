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
    $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']); //localhost:8000/api/login
    $router->post('register', ['as' => 'register', 'uses' => 'AuthController@register']); //localhost:8000/api/register
    $router->post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']); //localhost:8000/api/logout

    $router->group(['middleware' => 'auth'], function() use ($router) {
        
        //category
        $router->group(['prefix' => 'category'], function() use ($router) {
            $router->get('/', ['as' => 'category', 'uses' => 'Student\CategoryController@get_all_categories']); // localhost:8000/api/category
            $router->get('/{id}', ['as' => 'detail_category', 'uses' => 'Student\CategoryController@get_detail_category']); // localhost:8000/api/category/{id}
        });

        //category
        $router->group(['prefix' => 'classroom'], function() use ($router) {
            $router->post('/enroll', ['as' => 'enroll', 'uses' => 'Student\ClassroomController@enroll_classroom']); // localhost:8000/api/classroom/enroll
            $router->get('/', ['as' => 'classroom', 'uses' => 'Student\ClassroomController@get_all_classroom']); // localhost:8000/api/classroom/
            $router->get('/{id}', ['as' => 'detail_classroom', 'uses' => 'Student\ClassroomController@get_detail_classroom']); // localhost:8000/api/classroom/{id}
            $router->get('/get_topics/{id}', ['as' => 'class_topics', 'uses' => 'Student\ClassroomController@get_class_topics']); // localhost:8000/api/classroom/get_topics/{id}
            $router->get('/get_topics/{topic_id}/{id}', ['as' => 'class_topics', 'uses' => 'Student\ClassroomController@get_detail_theory']); // localhost:8000/api/classroom/get_topics/{topic_id}/{id}
        });

        //profile
        $router->group(['prefix' => 'profile'], function() use ($router){
            $router->get('/', ['as' => 'profile', 'uses' => 'Student\ProfileController@get_user_profile']); // localhost:8000/api/profile
            $router->get('/get_avatar/{id}', ['as' => 'get_avatar', 'uses' => 'Student\ProfileController@get_avatar']); // localhost:8000/api/profile/get_avatar/{id}
            $router->patch('/update_profile/{id}', ['as' => 'update_profile', 'uses' => 'Student\ProfileController@update_profile']); // localhost:8000/api/profile/update_profile/{id}
            $router->patch('/update_account/{id}', ['as' => 'update_account', 'uses' => 'Student\ProfileController@update_account']); // localhost:8000/api/profile/update_account/{id}
        });

        $router->group(['prefix' => 'admin'], function() use ($router){
            $router->get('/dashboard', ['as' => 'profile', 'uses' => 'Student\ProfileController@get_user_profile']); // localhost:8000/api/profile
            $router->group(['prefix' => 'classroom'], function() use ($router){
                
            });
        });

        $router->get('/controller', 'Controller@index');
    });
});
