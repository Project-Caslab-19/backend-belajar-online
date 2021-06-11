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

    //category
    $router->group(['prefix' => 'category'], function() use ($router) {
        $router->get('/', ['as' => 'category', 'uses' => 'Student\CategoryController@get_all_categories']); // localhost:8000/api/category
        $router->get('/{id}', ['as' => 'detail_category', 'uses' => 'Student\CategoryController@get_detail_category']); // localhost:8000/api/category/{id}
    });

    $router->group(['middleware' => 'auth'], function() use ($router) {

        //category
        $router->group(['prefix' => 'classroom'], function() use ($router) {
            $router->get('/quiz', ['as' => 'class_quiz', 'uses' => 'Student\ClassroomController@get_classroom_quiz']); // localhost:8000/api/classroom/quiz
            $router->get('/uncomplete', ['as' => 'class_uncomplete', 'uses' => 'Student\ClassroomController@get_uncomplete_class']); // localhost:8000/api/classroom/uncomplete
            $router->get('/complete', ['as' => 'class_complete', 'uses' => 'Student\ClassroomController@get_complete_class']); // localhost:8000/api/classroom/uncomplete
            $router->get('/get_topics/{id}', ['as' => 'class_topics', 'uses' => 'Student\ClassroomController@get_class_topics']); // localhost:8000/api/classroom/get_topics/{id}
            $router->get('/progress/{id}', ['as' => 'class_progress', 'uses' => 'Student\ClassroomController@get_classroom_progress']); // localhost:8000/api/classroom/get_topics/{id}
            $router->get('/get_topics/{topic_id}/{id}', ['as' => 'class_topics', 'uses' => 'Student\ClassroomController@get_detail_theory']); // localhost:8000/api/classroom/get_topics/{topic_id}/{id}

            $router->post('/enroll', ['as' => 'enroll', 'uses' => 'Student\ClassroomController@enroll_classroom']); // localhost:8000/api/classroom/enroll
        });

        //profile
        $router->group(['prefix' => 'profile'], function() use ($router){
            $router->get('/', ['as' => 'profile', 'uses' => 'Student\ProfileController@get_user_profile']); // localhost:8000/api/profile
            $router->get('/get_avatar/{id}', ['as' => 'get_avatar', 'uses' => 'Student\ProfileController@get_avatar']); // localhost:8000/api/profile/get_avatar/{id}
            $router->patch('/update_profile/{id}', ['as' => 'update_profile', 'uses' => 'Student\ProfileController@update_profile']); // localhost:8000/api/profile/update_profile/{id}
            $router->patch('/update_account/{id}', ['as' => 'update_account', 'uses' => 'Student\ProfileController@update_account']); // localhost:8000/api/profile/update_account/{id}
        });
        

        $router->group(['prefix' => 'quiz'], function() use ($router) {
            $router->get('/get_questions/{id_quiz}', ['as' => 'get_questions', 'uses' => 'Student\QuizController@get_questions']); 
            $router->post('/send_answer', ['as' => 'send_answer', 'uses' => 'Student\QuizController@send_answer']);
        });

        $router->get('/controller', 'Controller@index');

        //added
        $router->group(['prefix' => 'teacher'], function() use ($router){
            //dashboard
            $router->get('/dashboard', ['as' => 'dashboard', 'uses' => 'Teacher\DashboardController@get_dashboard_content']); // localhost:8000/api/teacher/dashboard
            $router->get('/categories', ['as' => 'categories', 'uses' => 'Teacher\CategoryController@get_category']); // localhost:8000/api/teacher/categories
            
            // class list and details (teacher version)
            $router->group(['prefix' => 'classroom'], function() use ($router){
                $router->get('/', ['as' => 'list_class', 'uses' => 'Teacher\ClassroomController@get_classroom']); // localhost:8000/api/profile
                $router->get('/{id}', ['as' => 'detail_class', 'uses' => 'Teacher\ClassroomController@get_detail_class']); // localhost:8000/api/profile
                $router->patch('/{id}', ['as' => 'edit_class', 'uses' => 'Teacher\ClassroomController@edit_classroom']);
                $router->post('/', ['as' => 'create_class', 'uses' => 'Teacher\ClassroomController@create_classroom']);
                $router->delete('/{id}', ['as' => 'delete_class', 'uses' => 'Teacher\ClassroomController@delete_classroom']);
            });

        });
    });

    $router->group(['prefix' => 'classroom'], function() use($router){
        $router->get('/', ['as' => 'classroom', 'uses' => 'Student\ClassroomController@get_all_classroom']); // localhost:8000/api/classroom/
        $router->get('/{id}', ['as' => 'detail_classroom', 'uses' => 'Student\ClassroomController@get_detail_classroom']); // localhost:8000/api/classroom/{id}
    });
});
