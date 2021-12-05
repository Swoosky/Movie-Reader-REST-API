<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('email/verify/{id}', 'VerificationController@verify')->name('verificationapi.verify'); // Make sure to keep this as your route name
Route::get('email/resend', 'VerificationController@resend')->name('verificationapi.resend');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('logout', 'Api\AuthController@logout');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('course', 'Api\CourseController@index');
    Route::get('course/{id}', 'Api\CourseController@show');
    Route::post('course', 'Api\CourseController@store');
    Route::put('course/{id}', 'Api\CourseController@update');
    Route::delete('course/{id}', 'Api\CourseController@destroy');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('student', 'Api\StudentController@index');
    Route::get('student/{id}', 'Api\StudentController@show');
    Route::post('student', 'Api\StudentController@store');
    Route::put('student/{id}', 'Api\StudentController@update');
    Route::delete('student/{id}', 'Api\StudentController@destroy');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user', 'Api\UserController@index');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::post('user', 'Api\UserController@store');
    Route::put('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('article', 'Api\ArticleController@index');
    Route::get('article/author/{author}', 'Api\ArticleController@indexOneUser');
    Route::get('article/{id}', 'Api\ArticleController@show');
    Route::post('article', 'Api\ArticleController@store');
    Route::put('article/{id}', 'Api\ArticleController@update');
    Route::delete('article/{id}', 'Api\ArticleController@destroy');
});