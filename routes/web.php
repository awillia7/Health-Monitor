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

Route::resource('screenings', 'ScreeningsController')->only('index', 'show', 'destroy');
Route::resource('questions', 'QuestionsController')->only('index');
Route::resource('tests', 'TestsController')->only('index', 'store');
Route::post('/questions/update-all', 'UpdateAllQuestionsController');
Route::resource('users', 'UsersController')->only('index', 'update');
Route::post('/users/search', "UserSearchController");
Route::post('screenings/search', "ScreeningSearchController");
Route::post('/screenings/submit', 'SubmitScreeningController');
Route::patch('/screenings/{screening}/override', 'OverrideScreeningController');
Route::put('/fail-score', 'UpdateFailScoreController');
Route::get('/testing-optin', 'TestingOptinFormController')->name('testing.optin');
Route::put('/testing-optin', 'TestingOptinController');
Route::get('/tests/upload', 'TestUploadFormController')->name('tests.upload');
