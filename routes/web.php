<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['https']], function () {

    Route::get('/', function () {
        if (Auth::check()):
            return Redirect::to('home');
        else:
            return view('auth.login');
        endif;
        return view('auth.login');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/logout', function () {
        Auth::logout();
        return Redirect::to('login');
    });
    Route::post('/tasks/updatestatus', 'TasksController@updateStatus');
    Route::post('/tickets/updatestatus', 'TicketsController@updateStatus');
    Route::post('/tickets/updatepic', 'TicketsController@updatePic');

    Route::post('/home/gettasksbymonthyear', 'HomeController@getTasksByMonthYear');
    Route::post('/home/getallusertasks', 'HomeController@getAllUserTasks');

    Route::resource('buildings', 'ApiBuildingsController');
    Route::resource('managementcompanies', 'ManagementCompaniesController');
    Route::resource('statuses', 'StatusesController');
    Route::resource('skus', 'SkusController');
    Route::resource('tasks', 'TasksController');
    Route::resource('tickets', 'TicketsController');
    Route::resource('durations', 'DurationsController');
    Route::resource('settings', 'SettingsController');
    Route::resource('users', 'UsersController');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');


});