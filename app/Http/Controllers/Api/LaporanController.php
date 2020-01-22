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
                    'performance' => [
                        'total_jam_kerja' => 10 + 20 + 30 + 40,
                        'total_terlambat' => 20,
                        'total_lembur' => 40,
                    ]
                ],
                'Ujay' => [
                    'minggu1' => 50,
                    'minggu2' => 60,
                    'minggu3' => 70,
                    'minggu4' => 80,
                    'performance' => [
                        'total_jam_kerja' => 50 + 60 + 70 + 80,
                        'total_terlambat' => 10,
                        'total_lembur' => 6,
                    ]
                ],
                'Bagus' => [
                    'minggu1' => 90,
                    'minggu2' => 100,
                    'minggu3' => 110,
                    'minggu4' => 120,
                    'performance' => [
                        'total_jam_kerja' => 90 + 100 + 110 + 120,
                        'total_terlambat' => 9,
                        'total_lembur' => 9,
                    ]
                ],
                'Kinay' => [
                    'minggu1' => 130,
                    'minggu2' => 140,
                    'minggu3' => 150,
                    'minggu4' => 160,
                    'performance' => [
                        'total_jam_kerja' => 130 + 140 + 150 + 160,
                        'total_terlambat' => 2,
                        'total_lembur' => 7,
                    ]
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
