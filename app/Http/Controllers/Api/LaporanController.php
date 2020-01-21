<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 200, 'message' => 'Sukses', 'data' => [
            'nama_bulan' => 'Januari',
            'total_jam_pegawai' => [
                'Rizki Maulidan' => [10, 20, 30, 40],
                'Najib Muhariri' => [10, 20, 30, 40],
                'Novil Fahlevy' => [10, 20, 30, 40],
                'Nabil Djulfiansyah' => [1, 1, 1, 1]
            ],
            'total_jam_per_bulan' => [100, 200, 300, 400],
            'status_pegawai' => [
                'terlambat' => 10,
                'tepat_waktu' => 40,
                'overwork' => 17
            ]
        ]]);
    }
}
