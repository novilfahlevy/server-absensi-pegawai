<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Absensi;

class DashboardController extends Controller
{
    public function index() {
        $total_pegawai = User::get()->count();
        $total_pegawai_absen = Absensi::get()->count();

        $sudah_absensi = [];
        foreach ( Absensi::all()->sortBy('absensi_masuk')->take(5) as $absen ) {
            $sudah_absensi[] = [
                'name' => $absen->user->name, 
                'absensi_masuk' => $absen->absensi_masuk,
                'absensi_keluar' => $absen->absensi_keluar,
                'id' => $absen->user->id
            ];
        }

        $belum_absensi = [];
        foreach ( User::all() as $user ) {
            if ( !Absensi::find($user->id) ) {
                $belum_absensi[] = ['name' => User::find($user->id)->name, 'id' => $user->id];
            }
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'total_pegawai' => $total_pegawai,
                'total_pegawai_absen' => $total_pegawai_absen,
                'total_pegawai_belum_absen' => $total_pegawai - $total_pegawai_absen,
                'total_pegawai_lembur' => 'Belum dibuat model lembur nya',
                'pegawai_sudah_absen' => $sudah_absensi,
                'pegawai_belum_absen' => collect($belum_absensi)->take(5)
            ]
        ]);
    }
}
