<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WaktuKerja;
use Illuminate\Support\Facades\DB;

class WaktuKerjaController extends Controller
{
    private $WaktuKerja;

    public function __construct()
    {
        $this->WaktuKerja = new WaktuKerja();
    }

    public function index()
    {
        $waktu_kerja = WaktuKerja::all();

        return response()->json(['status' => 200, 'message' => 'Sukses', 'data' => $waktu_kerja]);
    }

    public function tambahWaktuKerja(Request $request)
    {
        WaktuKerja::find(1)->update(['waktu_kerja' => $request->waktu_kerja, 'hari_kerja' => $request->hari_kerja]);

        $data = WaktuKerja::find(1)->get();

        return response()->json(['status' => 200, 'message' => 'Berhasil edit waktu kerja!', 'data' => $data]);
    }
}
