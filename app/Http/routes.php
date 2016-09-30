<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'], function(){

    Route::get('test', 'TestController@test');
//    Route::get('/home', function() {
//        return view('auth.login');
//    });
    Route::get('/', function () {

        Illuminate\Support\Facades\Redis::publish('rooms', json_encode(['room' => 'default_room']));
        return view('welcome');
    });

    Route::resource('messages', 'MessagesController');
    Route::post('messages/seen', 'MessagesController@filterDate');
    Route::resource('rooms', 'RoomsController');
    Route::post('users/set_room', 'UsersController@setRoom');
    Route::get('users/get_user', 'UsersController@getUser');
});
Route::get('auth/register', function () { return View('auth.registers'); });
Route::post('auth/register', 'Auth\RegisterController@create');
Route::controller('auth', 'Auth\AuthController');


