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
//Code here remote
Route::get('/users/{user}/restore','UserController@restore')->name('users.restore');

Route::get('/users/trash','UserController@trashed')->name('users.trashed');

Route::resource('users','UserController');
//Code here local
Route::patch('/users/{user}/trash','UserController@trash')->name('users.trash');

Route::resource('professions','ProfessionController');
//Adding content here from local
//Adding content here tag v.2
//Adding content from hey -b yariko
//Adding more content from local
//Adding even more conten into master
//Finishing the lesson1
//Finish the lesson1 again.

