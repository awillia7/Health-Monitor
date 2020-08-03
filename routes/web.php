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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);

Route::resource('screenings', 'ScreeningsController')->only('show', 'index');
Route::resource('questions', 'QuestionsController')->only('index');
Route::resource('users', 'UsersController')->only('index', 'update');
Route::post('/users/search', "UserSearchController");
Route::post('/screenings/submit', 'SubmitScreeningController');

