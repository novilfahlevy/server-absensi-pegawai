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
                [
                    'name' => 'Rizki Maulidan',
                    'minggu1' => 10,
                    'minggu2' => 20,
                    'minggu3' => 30,
                    'minggu4' => 40,
                    'performance' => [
                        'total_jam_kerja' => 10 + 20 + 30 + 40,
                        'total_terlambat' => 20,
                        'total_lembur' => 40,
                    ]
                ],
                [
                    'name' => 'Bagus',
                    'minggu1' => 50,
                    'minggu2' => 60,
                    'minggu3' => 70,
                    'minggu4' => 80,
                    'performance' => [
                        'total_jam_kerja' => 50 + 60 + 70 + 80,
                        'total_terlambat' => 10,
                        'total_lembur' => 8,
                    ]
                ],
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
