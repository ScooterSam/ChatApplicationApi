<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'api', 'middleware' => 'cors'], function() {

    Route::post('login', 'AuthController@login');

    Route::get('chats', 'ChatController@chats');
    Route::get('chat/{chat}', 'ChatController@messages');
    Route::post('create_chat', 'ChatController@createChat');
    Route::post('send_message', 'ChatController@sendMessage');

});
