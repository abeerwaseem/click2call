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

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/validate/{auth}', function (App\UserSip $sip) {
    return $sip;
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UserController');
Route::get('/users', 'UserController@index')->name('users');
Route::get('/add-user', 'UserController@create')->name('add-user');
Route::post('/add-user-post', 'UserController@store')->name('add-user-post');
Route::get('/edit-user/{user}', 'UserController@edit')->name('edit-user');
Route::post('/edit-user-post/{user}', 'UserController@update')->name('edit-user-post');
