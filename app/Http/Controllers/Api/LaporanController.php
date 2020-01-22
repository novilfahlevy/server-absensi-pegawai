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
                'Rizki Maulidan' => [
                    'minggu1' => 10,
                    'minggu2' => 20,
                    'minggu3' => 30,
                    'minggu4' => 40,
                    'total' => 10 + 20 + 30 + 40
                ],
                'Ujay' => [
                    'minggu1' => 50,
                    'minggu2' => 60,
                    'minggu3' => 70,
                    'minggu4' => 80,
                    'total' => 50 + 60 + 70 + 80
                ],
                'Bagus' => [
                    'minggu1' => 90,
                    'minggu2' => 100,
                    'minggu3' => 110,
                    'minggu4' => 120,
                    'total' => 90 + 100 + 110 + 120
                ],
                'Kinay' => [
                    'minggu1' => 130,
                    'minggu2' => 140,
                    'minggu3' => 150,
                    'minggu4' => 160,
                    'total' => 130 + 140 + 150 + 160
                ]
            ],
            'total_jam_per_bulan' => [100, 123, 124, 128],
            'status_pegawai' => [
                'terlambat' => 10,
                'tepat_waktu' => 40,
                'overwork' => 17
            ]
        ]]);
    }
}
