<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api', function () {

});
//Route::post('/auth', 'Api\MainController@authenticate');

Route::prefix('auth')->group(function () {
    Route::post('/register', 'Api\AuthController@register');
    Route::post('/login', 'Api\AuthController@login');
});

Route::prefix('buildings')->group(function () {
    Route::post('/getBuildings', 'Api\ApiBuildingsController@getBuildings');
    Route::post('/companyManagement', 'Api\ApiBuildingsController@getCompanyManagement');
    Route::post('/getTasks', 'Api\ApiBuildingsController@getTasksANdBuilding');
    Route::post('/edit', 'Api\ApiBuildingsController@edit');
    Route::post('/update', 'Api\ApiBuildingsController@update');
});

Route::prefix('home')->group(function () {
    Route::post('/index', 'Api\HomeController@index');
    Route::post('/getFullDateYear', 'Api\HomeController@getFullDateYear');
    Route::post('/getAllUserTasks', 'Api\HomeController@getAllUserTasks');
    Route::post('/getTasksByMonthYear', 'Api\HomeController@getTasksByMonthYear');
    Route::post('/getTasksByMonthYearOld', 'Api\HomeController@getTasksByMonthYearOld');
    Route::post('/getDoneTasksByMonth', 'Api\HomeController@getDoneTasksByMonth');
});
