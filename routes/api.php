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

Route::post('auth/login', 'Api\UserController@login')->name('login');
Route::get('unauthorized', 'Api\UserController@unauthorized')->name('unauthorized');
Route::get('/absensi/laporan/export', 'Api\LaporanController@export');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', 'Api\UserController@logout');

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::get('/user', 'Api\UserController@index');
        Route::get('/user/cari/{name}', 'Api\UserController@cari');
        Route::post('/user/store', 'Api\UserController@store');
        Route::get('/admin/waktuKerja', 'Api\WaktuKerjaController@index');
        Route::post('/admin/waktuKerja', 'Api\WaktuKerjaController@tambahWaktuKerja');
        Route::get('/absensi', 'Api\AbsensiController@index');

        Route::get('/absensi/laporan', 'Api\LaporanController@index');
        Route::get('/absensi/laporan/cari/{month}/{year}', 'Api\LaporanController@cari');
        Route::get('/absensi/{id}/detail', 'Api\AbsensiController@show');
        Route::get('/absensi/{keyword}', 'Api\AbsensiController@cari');
        Route::get('/dashboard', 'Api\DashboardController@index');
    });

    Route::group(['middleware' => ['role:Admin|User']], function () {
        Route::post('user/password', 'Api\UserController@editPassword');
        Route::post('user/edit', 'Api\UserController@editProfile');
        Route::get('/user/{id}', 'Api\UserController@show');
        Route::get('/lembur', 'Api\LemburController@index');
    });

    Route::group(['middleware' => ['role:User']], function () {
        Route::post('/user/absensiMasuk', 'Api\AbsensiController@absensiMasuk');
        Route::post('/user/absensiKeluar', 'Api\AbsensiController@absensiKeluar');
        Route::post('/user/ajukanLembur', 'Api\LemburController@create');
    });
});
