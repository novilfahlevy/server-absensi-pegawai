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

Route::post('/auth/login', 'Api\UserController@login')->name('login');
Route::get('/unauthorized', 'Api\UserController@unauthorized')->name('unauthorized');
Route::get('/absensi/laporan/export', 'Api\LaporanController@export');
Route::get('/absensi/laporan/export/{month}/{year}', 'Api\LaporanController@exportSelected');

// Android API
Route::group(['prefix' => 'mobile'], function () {
    Route::post('/auth/login', 'Api\Android\UserController@login');

    Route::group(['middleware' => ['auth:api']], function () {
        // User API
        Route::get('/getProfile/{id}', 'Api\Android\UserController@getProfile');
        Route::post('/gantiPassword', 'Api\Android\UserController@gantiPassword');
        Route::post('/changeProfilePicture', 'Api\Android\UserController@changeProfilePicture');

        // Absensi API
        Route::post('/absensiMasuk', 'Api\Android\AbsensiController@absensiMasuk');
        Route::get('/cekAbsensi/{user_id}', 'Api\Android\AbsensiController@cekAbsensi');
        Route::get('/getRiwayatAbsensi/{user_id}', 'Api\Android\AbsensiController@getRiwayatAbsensi');
        Route::get('/getDetailAbsensi/{user_id}', 'Api\Android\AbsensiController@getDetailAbsensiTodayDate');
        Route::get('/getDetailAbsensi/{user_id}/{tanggal}', 'Api\Android\AbsensiController@getDetailAbsensi');

        // Lembur API
        Route::post('/lembur', 'Api\Android\LemburController@lembur');
        Route::get('/riwayatLembur/{user_id}', 'Api\Android\LemburController@riwayatLembur');
        Route::get('/detailLembur/{user_id}/{tanggal}', 'Api\Android\LemburController@detailLembur');
    });
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/file/{name}', 'AbsensiController@file');
    Route::post('/auth/logout', 'Api\UserController@logout');

    Route::group(['middleware' => ['role:Admin']], function () {
        Route::post('/user/destroy/{id}', 'Api\UserController@destroy');
        Route::post('/user/edit/{id}', 'Api\UserController@editKredensial');

        // Job
        Route::post('/jobdesc/store', 'Api\JobdescController@store');
        Route::get('/jobdesc/{id}/show', 'Api\JobdescController@show');
        Route::post('/jobdesc/{id}/edit', 'Api\JobdescController@update');
        Route::delete('/jobdesc/{id}/destroy', 'Api\JobdescController@destroy');
        Route::put('/jobdesc/{replaced_job_id}/{new_job_id}', 'Api\JobdescController@replaceAllWith');

        // Absensi
        Route::get('/users/absen-masuk/by-admin', 'Api\AbsensiController@getUsersAbsenByAdmin');
        Route::get('/search/user/{name}/absen-by-admin', 'Api\AbsensiController@searchUsersAbsenByAdmin');
        Route::post('/absen-masuk/by-admin', 'Api\AbsensiController@absenMasukByAdmin');
        Route::get('/users/belum-absen', 'Api\AbsensiController@belumAbsen');

        Route::get('/users/absen-keluar/by-admin', 'Api\AbsensiController@getAbsensiByAdmin');
        Route::get('/search/absensi/{name}/absen-by-admin', 'Api\AbsensiController@searchUsersAbsensiByAdmin');
        Route::post('/absen-keluar/by-admin', 'Api\AbsensiController@absenKeluarByAdmin');

        Route::get('/dashboard', 'Api\DashboardController@index');
    });

    Route::group(['middleware' => ['role:Admin|Project Manager']], function () {
        // User
        Route::get('/user', 'Api\UserController@index');
        Route::get('/user/filter/{job}/{role}', 'Api\UserController@filter');
        Route::get('/user/cari/{name}', 'Api\UserController@cari');
        Route::post('/user/store', 'Api\UserController@store');

        // Waktu Kerja
        Route::get('/admin/waktuKerja', 'Api\WaktuKerjaController@index');
        Route::post('/admin/waktuKerja', 'Api\WaktuKerjaController@tambahWaktuKerja');

        // Lembur
        Route::get('/lembur/cari/{keyword}', 'Api\LemburController@cari');
        Route::get('/lembur/{id}/detail', 'Api\LemburController@show');
        Route::get('/lembur/riwayat/{id}', 'Api\LemburController@riwayatLemburById');
        Route::get('/lembur/{role}/{id}', 'Api\LemburController@index');
        Route::post('/lembur/byAdmin', 'Api\LemburController@lemburByAdmin');
        Route::post('/lembur/{id}', 'Api\LemburController@edit');
        Route::get('/lembur/filter/{role}/{id}/{month}/{year}', 'Api\LemburController@filter');
        // Absensi
        Route::get('/absensi', 'Api\AbsensiController@index');
        Route::get('/absensi/laporan', 'Api\LaporanController@index');
        Route::get('/absensi/laporan/cari/{month}/{year}', 'Api\LaporanController@cari');
        Route::get('/absensi/{id}/detail', 'Api\AbsensiController@show');
        Route::get('/absensi/riwayat', 'Api\AbsensiController@absensiHistory');
        Route::get('/absensi/riwayat/years', 'Api\AbsensiController@getAvailableAbsenYears');
        Route::get('/absensi/riwayat/filter/{year}/{month}', 'Api\AbsensiController@filterHistory');
        Route::get('/absensi/riwayat/search/{name}', 'Api\AbsensiController@searchHistory');
        Route::get('/absensi/{keyword}', 'Api\AbsensiController@cari');

        // Projet Manager
        Route::get('/user/pm', 'Api\ProjectManagerController@showPegawai');
        Route::get('/user/pm/filter/member/{id}/{job}', 'Api\ProjectManagerController@filterMember');
        Route::get('/user/pm/filter/pegawai/{job}', 'Api\ProjectManagerController@filterPegawai');
        Route::get('/user/{id}/pm', 'Api\ProjectManagerController@index');
        Route::get('/user/pm/{pm_id}/search/{keyword}', 'Api\ProjectManagerController@searchMember');
        Route::get('/user/pm/search/{keyword}', 'Api\ProjectManagerController@searchPegawai');
        Route::post('/user/pm', 'Api\ProjectManagerController@store');
        Route::delete('/user/pm/{pm_id}/{user_id}', 'Api\ProjectManagerController@destroy');

        // Izin
        Route::get('/users/to-izin', 'Api\IzinController@getUserToIzinByRole');
        Route::get('/search/users/{name}/to-izin', 'Api\IzinController@searchUserToIzinByRole');
        Route::post('/user/izin', 'Api\IzinController@izinUser');
        Route::get('/users/izin', 'Api\IzinController@getCurrentIzinByRole');
        Route::get('/users/izin/riwayat', 'Api\IzinController@getIzinRiwayatByRole');
        Route::get('/search/users/{name}/izin/riwayat', 'Api\IzinController@searchIzinRiwayatByRole');
        Route::delete('/izin/{id}/cancel', 'Api\IzinController@destroy');

        Route::get('/jobdesc', 'Api\JobdescController@index');
        Route::get('/role', 'Api\RoleController@index');
    });

    Route::group(['middleware' => ['role:Admin|User|Project Manager']], function () {
        Route::post('/user/password', 'Api\UserController@editPassword');
        Route::post('/user/edit', 'Api\UserController@editProfile');
        Route::get('/user/absensi', 'Api\AbsensiController@myAbsensi');
        Route::get('/user/{id}', 'Api\UserController@show');
    });

    // Route::group(['middleware' => ['role:User|Project Manager']], function () {
    //     // Android API
    //     Route::post('/user/absensiMasuk', 'Api\AbsensiController@absensiMasuk');
    //     Route::post('/user/absensiKeluar', 'Api\AbsensiController@absensiKeluar');
    //     Route::post('/user/ajukanLembur', 'Api\LemburController@create');
    //     // Route::get('/absensi/riwayat/last/{id}', 'Api\AbsensiController@riwayatAbsenTerakhir');
    //     // Route::get('/absensi/riwayat/user/{id}', 'Api\AbsensiController@absensiHistoryByUserId');
    // });
});
