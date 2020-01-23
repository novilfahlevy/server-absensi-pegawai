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

        $absensi = Absensi::all()->sortBy('absensi_masuk')->take(5);
        foreach ( $absensi as $key => $absen ) {
            $absensi[$key]['name'] = $absen->user->name;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'total_pegawai' => $total_pegawai,
                'total_pegawai_absen' => $total_pegawai_absen,
                'total_pegawai_belum_absen' => $total_pegawai - $total_pegawai_absen,
                'total_pegawai_lembur' => 'Belum dibuat model lembur nya',
                'pegawai_sudah_absen' => $absensi,
                'pegawai_minta_lembur' => 'Belum dibuat model lembur nya'
            ]
        ]);
    }
}
