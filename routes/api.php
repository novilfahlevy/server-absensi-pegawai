<?php

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

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

Route::post('auth/login', 'Api\UserController@login');
Route::post('auth/register', 'Api\UserController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/user', 'Api\UserController@index');
        Route::get('/user/{id}', 'Api\UserController@show');
        Route::post('/user/store', 'Api\UserController@store');
    });

    Route::group(['middleware' => ['role:Admin|User']], function () {
        Route::post('user/password', 'Api\UserController@editPassword');
        Route::post('user/edit', 'Api\UserController@editProfile');
    });
});

