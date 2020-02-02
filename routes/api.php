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
Route::get('/absensi/laporan/export/{month}/{year}', 'Api\LaporanController@exportSelected');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', 'Api\UserController@logout');

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::post('/user/destroy/{id}', 'Api\UserController@destroy');
        Route::post('/user/edit/{id}', 'Api\UserController@editKredensial');
        Route::post('/jobdesc/store', 'Api\JobdescController@store');
        Route::get('/jobdesc/{id}/show', 'Api\JobdescController@show');
        Route::post('/jobdesc/{id}/edit', 'Api\JobdescController@update');
        Route::delete('/jobdesc/{id}/destroy', 'Api\JobdescController@destroy');
        Route::put('/jobdesc/{replaced_job_id}/{new_job_id}', 'Api\JobdescController@replaceAllWith');
    });

    Route::group(['middleware' => ['role:Admin|Project Manager']], function () {
        Route::get('/lembur/cari/{keyword}', 'Api\LemburController@cari');
        Route::get('/lembur/{id}/detail', 'Api\LemburController@show');
        Route::get('/user', 'Api\UserController@index');
        Route::get('/user/filter/{job}/{role}', 'Api\UserController@filter');
        Route::get('/user/cari/{name}', 'Api\UserController@cari');
        Route::post('/user/store', 'Api\UserController@store');
        Route::get('/admin/waktuKerja', 'Api\WaktuKerjaController@index');
        Route::post('/admin/waktuKerja', 'Api\WaktuKerjaController@tambahWaktuKerja');
        Route::get('/absensi', 'Api\AbsensiController@index');
        Route::get('/lembur/{role}/{id}', 'Api\LemburController@index');
        Route::post('/lembur/{id}', 'Api\LemburController@edit');

        Route::get('/lembur/filter/{role}/{id}/{month}/{year}', 'Api\LemburController@filter');
        Route::get('/absensi/laporan', 'Api\LaporanController@index');
        Route::get('/absensi/laporan/cari/{month}/{year}', 'Api\LaporanController@cari');
        Route::get('/absensi/{id}/detail', 'Api\AbsensiController@show');
        Route::get('/absensi/riwayat', 'Api\AbsensiController@absensiHistory');
        Route::get('/absensi/riwayat/years', 'Api\AbsensiController@getAvailableAbsenYears');
        Route::get('/absensi/riwayat/filter/{year}/{month}', 'Api\AbsensiController@filterHistory');
        Route::get('/absensi/riwayat/search/{name}', 'Api\AbsensiController@searchHistory');
        Route::get('/absensi/{keyword}', 'Api\AbsensiController@cari');
        Route::get('/dashboard', 'Api\DashboardController@index');

        Route::get('/user/pm', 'Api\ProjectManagerController@showPegawai');
        Route::get('/user/pm/filter/member/{id}/{job}', 'Api\ProjectManagerController@filterMember');
        Route::get('/user/pm/filter/pegawai/{job}', 'Api\ProjectManagerController@filterPegawai');
        Route::get('/user/{id}/pm', 'Api\ProjectManagerController@index');
        Route::get('/user/pm/{pm_id}/search/{keyword}', 'Api\ProjectManagerController@searchMember');
        Route::get('/user/pm/search/{keyword}', 'Api\ProjectManagerController@searchPegawai');
        Route::post('/user/pm', 'Api\ProjectManagerController@store');
        Route::delete('/user/pm/{pm_id}/{user_id}', 'Api\ProjectManagerController@destroy');

        Route::get('/jobdesc', 'Api\JobdescController@index');
        Route::get('/role', 'Api\RoleController@index');
    });

    Route::group(['middleware' => ['role:Admin|User|Project Manager']], function () {
        Route::post('user/password', 'Api\UserController@editPassword');
        Route::post('user/edit', 'Api\UserController@editProfile');
        Route::get('/user/{id}', 'Api\UserController@show');
    });

    Route::group(['middleware' => ['role:User|Project Manager']], function () {
        Route::post('/user/absensiMasuk', 'Api\AbsensiController@absensiMasuk');
        Route::post('/user/absensiKeluar', 'Api\AbsensiController@absensiKeluar');
        Route::post('/user/ajukanLembur', 'Api\LemburController@create');
    });
});
